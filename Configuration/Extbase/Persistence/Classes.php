<?php
declare(strict_types = 1);

use GeorgRinger\Eventnews\Domain\Model\News;

return [
    //Klasse des Models
    News::class => [
        //Name der Tabelle auf welches das Model gemapped wird
        'tableName' => 'tx_news_domain_model_news',
    ],
];
