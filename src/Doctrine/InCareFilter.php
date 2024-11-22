<?php
namespace App\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class InCareFilter extends SQLFilter
{
    public function addFilterConstraint(
        ClassMetadata $targetEntity,
        $targetTableAlias
    ): string {
        if (!$targetEntity->hasField('in_care')) {
            return '';
        }

        return sprintf('%s.in_care = true', $targetTableAlias);
    }
}