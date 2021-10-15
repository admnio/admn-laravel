<?php

namespace Auditit\AudititLaravel;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 *
 */
class AuditLogger
{
    /**
     * @var
     */
    protected $source;
    /**
     * @var array
     */
    protected $actor = [];
    /**
     * @var
     */
    protected $action;
    /**
     * @var array
     */
    protected $context = [];
    /**
     * @var array
     */
    protected $properties = [];
    /**
     * @var array
     */
    protected $entities = [];

    /**
     * @var null
     */
    protected $nonce = null;

    /**
     * @return AuditLogger
     */
    public static function new()
    {
        return (new self());
    }

    /**
     * @param $source
     * @param string|array $actor
     * @param $action
     * @param array $properties
     * @param array $context
     * @return array|void
     */
    public static function create($source, $actor, $action, $properties = [], $context = []){
        $builder = AuditLogger::new()
            ->source($source)
            ->action($action);

        if(is_array($actor)){
            $builder->actor(...$actor);
        }else{
            $builder->actor($actor);
        }

        if(empty($properties) === false){
            foreach($properties as $property => $value){
                $builder->addProperty($property,$value);
            }
        }

        if(empty($context) === false){
            $builder->context($context);
        }

       return $builder->save();
    }

    /**
     *
     */
    public function save()
    {
        if (empty(config('audit_logger.token')) || empty(config('audit_logger.secret'))) {
            return;
        }

        $response = Http::withHeaders([
            'ApiToken'     => config('audit_logger.token'),
            'ApiSecret'    => config('audit_logger.secret'),
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ])->post(config('app.url') . '/api/intake', [
            'source'     => $this->source,
            'actor'      => $this->actor,
            'action'     => $this->action,
            'entities'   => $this->entities,
            'context'    => $this->context,
            'properties' => $this->properties,
            'nonce'      => empty($this->nonce) ? Str::uuid()->toString() : $this->nonce,
        ]);

        if ($response->ok() === false) {
            Log::debug('Audit Logger Request', $response->json());
        }

        return [
            'success'  => $response->ok(),
            'contents' => $response->body(),
        ];
    }

    /**
     * @param $source
     * @return $this
     */
    public function source($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @param $actorSourceIdentifier
     * @param null $displayAs
     * @param null $type
     * @return $this
     */
    public function actor($actorSourceIdentifier, $displayAs = null, $type = null)
    {
        $this->actor = [
            'source_identifier' => $actorSourceIdentifier,
            'display_as'        => $displayAs,
            'type'              => $type,
        ];

        return $this;
    }

    /**
     * @param $action
     * @return $this
     */
    public function action($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @param array $context
     * @return $this
     */
    public function context(array $context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @param $property
     * @param $value
     * @return $this
     */
    public function addProperty($property, $value)
    {
        $this->properties[] = [
            'property' => $property,
            'value'    => $value,
        ];

        return $this;
    }

    /**
     * @param $entitySourceIdentifier
     * @param $displayAs
     * @param null $type
     * @return $this
     */
    public function addEntity($entitySourceIdentifier, $displayAs, $type = null)
    {
        $this->entities[] = [
            'source_identifier' => $entitySourceIdentifier,
            'display_as'        => $displayAs,
            'type'              => $type,
        ];

        return $this;
    }

    /**
     * @param string $nonce
     */
    public function nonce(string $nonce)
    {
        $this->nonce = $nonce;
    }
}
