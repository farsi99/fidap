<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class Pagination
{
    private $page;
    private $manager;
    private $limit = 6;
    private $entityClass;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * cette méthode traite le caclul de nombre total de page
     */
    public function getTotalPage($typeArticle = [])
    {
        if (empty($this->entityClass)) {
            throw new \Exception("Vous devez spécifier une entité class");
        }
        $total = $this->manager->getRepository($this->entityClass)->findBy($typeArticle);
        return ceil(count($total) / $this->limit);
    }

    /**
     * cette méthode retourne l'ensemble des données 
     */
    public function getData($typeArticle = [])
    {
        if (empty($this->entityClass)) {
            throw new \Exception("Vous devez spécifier une entité class");
        }
        $start = $this->page * $this->limit - $this->limit;
        $datas = $this->manager->getRepository($this->entityClass)->findBy($typeArticle, ['id' => 'DESC'], $this->limit, $start);
        return $datas;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setPage($page)
    {

        $this->page = $page;
        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }
}
