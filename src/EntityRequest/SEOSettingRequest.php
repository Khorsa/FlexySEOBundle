<?php

namespace flexycms\FlexySEOBundle\EntityRequest;


use flexycms\FlexySEOBundle\Entity\SEOSetting;
use flexycms\FlexySEOBundle\Repository\SEOSettingsRepository;

/**
 * Промежуточный класс для создания формы редактирования статьи
 * Class SEOSettingRequest
 */
class SEOSettingRequest// implements EntityRequestInterface
{
    private $repository;

    /**
     * @var SEOSetting
     */
    private $item;
    private $isNew;

    public $route = '';
    public $title = '';
    public $description = '';
    public $keywords = '';
    public $ogImage = '';
    public $ogTitle  = '';
    public $ogDescription = '';


    private $backPath;

    public function __construct(SEOSettingsRepository $repository)
    {
        $this->repository = $repository;
        $this->backPath = '';
    }


    /**
     * @return SEOSetting
     */
    public function get(): SEOSetting
    {
        return $this->item;
    }


    //Создание новой статьи
    public function create()
    {
        $this->item = new SEOSetting();

        $this->route = '';
        $this->title = '';
        $this->description = '';
        $this->keywords = '';
        $this->ogImage = '';
        $this->ogTitle = '';
        $this->ogDescription = '';

        $this->isNew = true;
    }

    public function load($id)
    {
        $this->item = $this->repository->find($id);

        if (!$this->item) throw new \Exception("Настройка  {$id} не найдена!");

        $this->isNew = false;

        $this->route = $this->item->getRoute();
        $this->title = $this->item->getTitle();
        $this->description = $this->item->getDescription();
        $this->keywords = $this->item->getKeywords();
        $this->ogImage = $this->item->getOgImage();
        $this->ogTitle = $this->item->getOgTitle();
        $this->ogDescription = $this->item->getDescription();
    }



    public function save()
    {
        $this->item->setRoute($this->route);
        $this->item->setTitle($this->title);
        $this->item->setDescription($this->description);
        $this->item->setKeywords($this->keywords);
        $this->item->setOgImage($this->ogImage);
        $this->item->setOgTitle($this->ogTitle);
        $this->item->setOgDescription($this->ogDescription);

        if ($this->isNew) {
            $this->repository->create($this->item);
        } else {
            $this->repository->update($this->item);
        }
    }


    public function getFormModifiers(): array
    {
        return [];
    }


    /**
     * @return string
     */
    public function getBackPath(): string
    {
        return $this->backPath;
    }

    /**
     * @param string $backPath
     * @return $this
     */
    public function setBackPath(string $backPath): self
    {
        $this->backPath = $backPath;
        return $this;
    }

}