<?php

namespace PrestaShop\Module\MyModule\Controller\Admin;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyModuleController extends FrameworkBundleAdminController
{
    const TAB_CLASS_NAME = 'AdminMyModule';
    
    /**
     * @Route("/mymodule/configure", name="mymodule_admin_configure", methods={"GET"})
     */
    public function configure(): Response
    {
        return $this->render('@Modules/mymodule/views/templates/admin/configure.html.twig', [
            'some_variable' => 'Hello from Symfony!',
        ]);
    }
}
