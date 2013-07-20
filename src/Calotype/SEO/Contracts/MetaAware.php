<?php namespace Calotype\SEO\Contracts;

interface MetaAware
{
    public function getMetaTitle();
    public function getMetaTitleSuffix();
    public function getMetaDescription();
}
