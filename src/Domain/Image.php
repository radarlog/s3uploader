<?php
declare(strict_types=1);

namespace Radarlog\S3Uploader\Domain;

final class Image
{
    /** @var Image\Identity */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $hash;

    /** @var \DateTimeImmutable */
    private $uploadedAt;

    public function __construct(Image\Identity $id, string $hash, string $name)
    {
        $this->id = $id;
        $this->hash = $hash;
        $this->name = $name;
        $this->uploadedAt = new \DateTimeImmutable();
    }

    public function id(): string
    {
        return $this->id->toString();
    }

    public function getState(): Image\State
    {
        return new Image\State([
            'uuid' => $this->id->toString(),
            'hash' => $this->hash,
            'name' => $this->name,
            'uploaded_at' => $this->uploadedAt->format(Image\State::DATETIME_FORMAT),
        ]);
    }

    public static function fromState(Image\State $state): self
    {
        $state = $state->asArray();

        $id = new Image\Identity($state['uuid']);

        $image = new self($id, $state['hash'], $state['name']);

        $image->uploadedAt = new \DateTimeImmutable($state['uploaded_at']);

        return $image;
    }
}
