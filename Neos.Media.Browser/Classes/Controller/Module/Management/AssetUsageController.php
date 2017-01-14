<?php
namespace Neos\Media\Browser\Controller\Module\Management;

/*
 * This file is part of the Neos.Media.Browser package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Service\ContentDimensionPresetSourceInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Service\AssetService;
use Neos\Neos\Domain\Model\Dto\AssetUsageInNodeProperties;
use Neos\Neos\Service\UserService;

/**
 * Controller for asset usage handling
 */
class AssetUsageController extends ActionController
{
    /**
     * @Flow\Inject
     * @var UserService
     */
    protected $userService;

    /**
     * @Flow\Inject
     * @var AssetService
     */
    protected $assetService;

    /**
     * @Flow\Inject
     * @var ContentDimensionPresetSourceInterface
     */
    protected $contentDimensionPresetSource;

    /**
     * Get Related Nodes for an asset
     *
     * @param AssetInterface $asset
     * @return void
     */
    public function relatedNodesAction(AssetInterface $asset)
    {
        $userWorkspace = $this->userService->getPersonalWorkspace();

        $usageReferences = $this->assetService->getUsageReferences($asset);
        $relatedNodes = [];

        /** @var AssetUsageInNodeProperties $usage */
        foreach ($usageReferences as $usage) {
            $documentNodeIdentifier = $usage->getDocumentNode() instanceof NodeInterface ? $usage->getDocumentNode()->getIdentifier() : null;

            $relatedNodes[$usage->getSite()->getNodeName()]['site'] = $usage->getSite();
            $relatedNodes[$usage->getSite()->getNodeName()]['documentNodes'][$documentNodeIdentifier]['node'] = $usage->getDocumentNode();
            $relatedNodes[$usage->getSite()->getNodeName()]['documentNodes'][$documentNodeIdentifier]['nodes'][] = [
                'node' => $usage->getNode(),
                'nodeData' => $usage->getNode()->getNodeData(),
                'contextDocumentNode' => $usage->getDocumentNode(),
                'accessible' => $usage->isAccessible()
            ];
        }

        $this->view->assignMultiple([
            'asset' => $asset,
            'relatedNodes' => $relatedNodes,
            'contentDimensions' => $this->contentDimensionPresetSource->getAllPresets(),
            'userWorkspace' => $userWorkspace
        ]);
    }
}
