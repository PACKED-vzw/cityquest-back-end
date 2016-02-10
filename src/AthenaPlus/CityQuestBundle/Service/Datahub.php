<?php
/**
 * Created by PhpStorm.
 * User: pieter
 * Date: 10/02/16
 * Time: 11:16
 */

namespace AthenaPlus\CityQuestBundle\Service;



class Datahub
{
    protected function convertListToItems($item_list)
    {
        // Making some assumptions
        /*
         * :[{"itemid":"gm5x8rs28s77dvxfz572h2","title":"New item","hints":[],"media":"","qrcode":"0917bj5lmj1b3u91g3avrzm","order":0}],
         * "[{"itemid":"gm5x8rs28s77dvxfz572h2","title":"New item","hints":[],"media":"","qrcode":"0917bj5lmj1b3u91g3avrzm","order":"0","remote_image":"http:\/\/cityquest.be\/","descriptionItem":"A desc","crypticDescriptionItem":"No desc"}]"
         */
        $itemContainers = [];
        foreach($item_list as $single_item) {
            $itemContainers[] = [
                'title' => $single_item['name'],
                'descriptionItem' => $single_item['description'],
                'itemid' => hash('md5', time()),
                'hits' => [],
                'media' => '',
                'qrcode' => hash('md5', time()),
                'order' => 0
            ];
        }
        return $itemContainers;
    }

    public function getFromDatahub()
    {
        // Get the items from the datahub, using a curated endpoint
        $response = $this->get('http')->getListOfNiceItems();
        return $this->convertListToItems($response['records']);
    }

}