<?php

namespace Pionect\Backoffice\ContentElements\Tables;


interface TableFilterContract
{
    public function getName();

    public function getDescription();

    public function getValues();
}