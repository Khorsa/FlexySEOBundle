<?php

namespace flexycms\FlexySEOBundle\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use flexycms\FlexySEOBundle\Entity\SEOSetting;
use flexycms\FlexySEOBundle\EntityRequest\SEOSettingRequest;
use Symfony\Component\Routing\RouterInterface;

/**
 * @method SEOSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method SEOSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method SEOSetting[]    findAll()
 * @method SEOSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SEOSettingsRepository extends ServiceEntityRepository
{
    private $dataManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $dataManager)
    {
        $this->dataManager = $dataManager;
        parent::__construct($registry, SEOSetting::class);
    }


    public function create(SEOSetting $setting): SEOSetting
    {
        $this->dataManager->persist($setting);
        $this->dataManager->flush();

        return $setting;
    }

    public function update(SEOSetting $setting): SEOSetting
    {
        $this->dataManager->flush();

        return $setting;
    }

    public function delete(SEOSetting $rubric)
    {
        $this->dataManager->remove($rubric);
        $this->dataManager->flush();
    }

    public function checkAndCreate(RouterInterface $router)
    {
        $items = $this->findAll();
        $allRoutes = $router->getRouteCollection()->all();
        $allRoutesKey = [];
        foreach($allRoutes as $key => $route) {
            if (strpos($key, '_') === 0) continue;
            if (strpos($key, 'admin') === 0) continue;
            if (strpos($key, 'app_') === 0) continue;
            $allRoutesKey[] = $key;
        }


        // Проверяем, есть ли в реальных маршрутах все роуты из базы, если нет - удаляем
        foreach($items as $item) {
            if ($item->getRoute() != '' && in_array($item->getRoute(), $allRoutesKey)) continue;
            $this->delete($item);
        }

        // Проверяем, есть ли в базе реальные роуты, если нет - добавляем
        foreach($allRoutesKey as $key) {

            if ($this->findBy(['route' => $key])) continue;

            $new = new SEOSettingRequest($this);
            $new->create();
            $new->route = $key;
            $new->save();
        }
    }
}
