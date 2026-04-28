<?php
namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Category;
use App\Entity\Operation as OperationEntity;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

class UserScopedExtension implements
    QueryCollectionExtensionInterface,
    QueryItemExtensionInterface
{
    public function __construct(private Security $security) {}

    private function addWhere(QueryBuilder $qb, string $resourceClass): void
    {
        $user = $this->security->getUser();
        if (!$user) {
            return;
        }

        if (
            in_array($resourceClass, [OperationEntity::class, Category::class])
        ) {
            $rootAlias = $qb->getRootAliases()[0];
            $qb->andWhere("$rootAlias.user = :user")->setParameter(
                "user",
                $user,
            );
        }
    }

    public function applyToCollection(
        QueryBuilder $qb,
        QueryNameGeneratorInterface $nameGenerator,
        string $resourceClass,
        ?Operation $operation = null,
        array $context = [],
    ): void {
        $this->addWhere($qb, $resourceClass);
    }

    public function applyToItem(
        QueryBuilder $qb,
        QueryNameGeneratorInterface $nameGenerator,
        string $resourceClass,
        array $identifiers,
        ?Operation $operation = null,
        array $context = [],
    ): void {
        $this->addWhere($qb, $resourceClass);
    }
}
