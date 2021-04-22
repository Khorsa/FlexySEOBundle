<?php

namespace flexycms\FlexySEOBundle\Controller;


use flexycms\FlexyAdminFrameBundle\Controller\AdminBaseController;
use flexycms\BreadcrumbsBundle\Utils\Breadcrumbs;
use flexycms\FlexySEOBundle\EntityRequest\SEOSettingRequest;
use flexycms\FlexySEOBundle\Form\SeoSettingType;
use flexycms\FlexySEOBundle\Repository\SEOSettingsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminSEOSettingsController extends AdminBaseController
{
    private $repository;

    public function __construct(SEOSettingsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/seosettings", name="admin_seosettings")
     */
    public function list()
    {



        $forRender = parent::renderDefault();
        $forRender['title'] = "SEO-настройки разделов";

        $this->repository->checkAndCreate($this->container->get('router'));

        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->prepend($this->generateUrl("admin_seosettings"), 'SEO-настройки');
        $breadcrumbs->prepend($this->generateUrl("admin_home"), 'Главная');
        $forRender['breadcrumbs'] = $breadcrumbs;

        $forRender['settings'] = $this->repository->findAll();

        $forRender['ajax'] = $this->generateUrl("admin_seosettings_json");

        return $this->render('@FlexySEO/list.html.twig', $forRender);
    }


    /**
     * @Route("/admin/seosettings.json", name="admin_seosettings_json")
     */
    public function listJSON(Request $request)
    {
        $sort = $request->get("sort");


        $settings = $this->repository->findAll();

        $draw = $request->get("draw");
        $recordsTotal = count($settings);
        $recordsFiltered = count($settings);

        $data = array();
        foreach($settings as $setting)
        {
            $item = array();
            $item[] = '<a href="' . $this->generateUrl("admin_seosettings_edit", ['id' => $setting->getId()]) . '" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>';
            $item[] = $setting->getTitle();
            $item[] = $setting->getRoute();
            $item[] = $this->generateUrl($setting->getRoute());

            $data[] = $item;
        }

        return $this->json([
            "data" => $data,
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,

        ]);
    }








    /**
     * @Route("/admin/seosettings/edit", name="admin_seosettings_edit")
     */
    public function edit(Request $request, SEOSettingRequest $itemRequest)
    {
        $id = $request->get('id');
        $setting = $this->repository->find($id);
        if ($setting === null) {
            $this->addFlash("danger", "Такого маршрута нет");
            return $this->redirectToRoute('admin_seosettings');
        }

        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->prepend($this->generateUrl("admin_templates"), 'Редактирование «'.$setting->getRoute().'»');
        $breadcrumbs->prepend($this->generateUrl("admin_seosettings"), 'SEO-настройки');
        $breadcrumbs->prepend($this->generateUrl("admin_home"), 'Главная');
        $forRender['breadcrumbs'] = $breadcrumbs;

        $itemRequest->load($id);





        $form = $this->createForm(SeoSettingType::class, $itemRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $itemRequest->save();

            if ($form->get('apply')->isClicked()) {
                return $this->redirectToRoute("admin_seosettings_edit", ['id' => $itemRequest->get()->getId()] );
            }

            return $this->redirectToRoute("admin_seosettings");
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = "Редактирование маршрута";
        $forRender['form'] = $form->createView();
        $forRender['breadcrumbs'] = $breadcrumbs;
        $forRender['backUrl'] = $this->generateUrl("admin_seosettings");

        return $this->render("@FlexyAdminFrame/simpleform.html.twig", $forRender);
    }




}