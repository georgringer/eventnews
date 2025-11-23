<?php

$iconList = [];
foreach (['ext-news-type-event' => 'news_domain_model_news_event.svg',
    'apps-pagetree-folder-contains-eventnews' => 'ext-eventnews-folder-tree.svg',
] as $identifier => $path) {
    $iconList[$identifier] = [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:eventnews/Resources/Public/Icons/' . $path,
    ];
}

return $iconList;
