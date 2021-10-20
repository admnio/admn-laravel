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
    protected $tags = [];
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
     * @param array $tags
     * @param array $context
     * @return array|void
     */
    public static function create($source, $actor, $action, $tags = [], $context = [])
    {
        $builder = AuditLogger::new()
            ->source($source)
            ->action($action)
            ->actor($actor);


        foreach ($tags as $tag) {
            $builder->addTag($tag);
        }


        if (empty($context) === false) {
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
            'source'   => $this->source,
            'actor'    => $this->actor,
            'action'   => $this->action,
            'entities' => $this->entities,
            'context'  => $this->context,
            'tags'     => $this->tags,
            'nonce'    => empty($this->nonce) ? Str::uuid()->toString() : $this->nonce,
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
     * @param string $type
     * @return $this
     */
    public function actor($actor)
    {
        $this->actor = $actor;

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
     * @param $tag
     * @return $this
     */
    public function addTag($tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * @param $entitySourceIdentifier
     * @param $displayAs
     * @param null $type
     * @return $this
     */
    public function addEntity($entity)
    {
        $this->entities[] = $entity;

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
