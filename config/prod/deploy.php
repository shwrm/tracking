<?php declare(strict_types=1);
use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;
return new class extends DefaultDeployer
{
    public function configure()
    {
        $builder = $this->getConfigBuilder();
        $builder
            ->useSshAgentForwarding(true)
            ->server('tracking@18.184.210.74')
            ->deployDir('/home/tracking')
            ->repositoryUrl('git@github.com:shwrm/tracking.git')
            ->repositoryBranch('master')
            ->installWebAssets(false)
        ;
        return $builder;
    }
};
