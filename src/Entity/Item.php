<?php

namespace App\Entity;

use App\Entity\Traits\TimeStampable;
use App\GraphQL\PrimitiveType;
use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use JMS\Serializer\Annotation\Groups as AnnotationGroups;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Item
{
    use TimeStampable;

    final public const GROUP_GET = 'item_get';
    final public const GROUP_POST = 'item_post';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[AnnotationGroups([self::GROUP_GET])]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[AnnotationGroups([self::GROUP_GET, self::GROUP_POST])]
    private ?string $name = null;

    /**
     * @var Collection<int,Price>
     */
    #[ORM\OneToMany(mappedBy: 'item', targetEntity: Price::class, cascade: ['remove'])]
    #[AnnotationGroups([self::GROUP_GET, self::GROUP_POST])]
    private Collection $price;

    #[ORM\Column(type:'integer', nullable:false)]
    private int $amount;


    public function __construct()
    {
        $this->price = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int,Price>
     */
    public function getPrices(): Collection
    {
        return $this->price;
    }

    public function addPrice(Price $price): self
    {
        if (!$this->price->contains($price)) {
            $this->price[] = $price;
            $price->setItem($this);
        }

        return $this;
    }

    public function removePrice(Price $price): self
    {
        if ($this->price->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getItem() === $this) {
                $price->setItem(null);
            }
        }

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
