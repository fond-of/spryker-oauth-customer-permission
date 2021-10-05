<?php

namespace FondOfSpryker\Zed\OauthCustomerPermission\Persistence\Propel\Mapper;

use FondOfSpryker\Zed\OauthCustomerPermission\Persistence\OauthCustomerPermissionRepository;
use Generated\Shared\Transfer\PermissionTransfer;
use Orm\Zed\Permission\Persistence\Base\SpyPermission;
use Propel\Runtime\Collection\ObjectCollection;

class PermissionMapper implements PermissionMapperInterface
{
    public const CONFIGURATION_ID_COMPANIES = 'id_companies';

    /**
     * @param \Orm\Zed\Permission\Persistence\Base\SpyPermission $entity
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer
     */
    public function mapEntityToTransfer(SpyPermission $entity): PermissionTransfer
    {
        return (new PermissionTransfer())->fromArray($entity->toArray(), true)
            ->setConfigurationSignature(json_decode($entity->getConfigurationSignature(), true));
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Permission\Persistence\Base\SpyPermission[] $entityCollection
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer[]
     */
    public function mapEntityCollectionToTransfers(ObjectCollection $entityCollection): array
    {
        /** @var \Generated\Shared\Transfer\PermissionTransfer[] $transfers */
        $transfers = [];

        foreach ($entityCollection as $entity) {
            $key = $entity->getKey();

            if (!array_key_exists($key, $transfers)) {
                $transfers[$key] = $this->mapEntityToTransfer($entity);
            }

            $configuration = $transfers[$key]->getConfiguration();
            $idCompany = $entity->getVirtualColumn(OauthCustomerPermissionRepository::VIRTUAL_COL_FK_COMPANY);

            if (!array_key_exists(static::CONFIGURATION_ID_COMPANIES, $configuration)) {
                $configuration[static::CONFIGURATION_ID_COMPANIES] = [];
            }

            if (in_array($idCompany, $configuration[static::CONFIGURATION_ID_COMPANIES], true)) {
                continue;
            }

            $configuration['id_companies'][] = $idCompany;
            $transfers[$key]->setConfiguration($configuration);
        }

        return array_values($transfers);
    }
}
