<?php

namespace App\Entity;

use App\Entity\Enum\PriceTypeEnum;
use App\Repository\PriceRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups as AnnotationGroups;
use Overblog\GraphQLBundle\Annotation as GQL;


#[ORM\Entity(repositoryClass: PriceRepository::class)]
class Price
{
    final public const GROUP_GET = 'price_get';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[AnnotationGroups([self::GROUP_GET])]
    private ?int $id = null;

    #[ORM\Column(type: 'price_type_enum')]
    #[AnnotationGroups([self::GROUP_GET])]
    private string $type;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[AnnotationGroups([self::GROUP_GET])]
    private ?int $amount = null;


    #[ORM\ManyToOne(targetEntity: Item::class, inversedBy: 'prices')]
    #[AnnotationGroups([self::GROUP_GET])]
    private ?Item $item = null;

    /** @var array<string,mixed>|null */
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $variables = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $calcul = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getType(): string
    {
        return $this->type;
    }

    public function setType(PriceTypeEnum|string $type): self
    {
        if (!is_string($type)) {
            $type = $type->value;
        }
        $this->type = $type;

        return $this;
    }
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    /** @return array<string,mixed>|null */
    public function getVariables(): ?array
    {
        return $this->variables;
    }

    /** @param array<string,mixed>|null $variables */
    public function setVariables(?array $variables): self
    {
        $this->variables = $variables;

        return $this;
    }

    public function getCalcul(): ?string
    {
        return $this->calcul;
    }

    public function setCalcul(?string $calcul): self
    {
        $this->calcul = $calcul;

        return $this;
    }
}
