<?php

namespace MyModule\Controller;

use MyModule\Form\TrialKitType;
use MyModule\Service\DataService;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class AdminMyModuleController extends FrameworkBundleAdminController
{
    const TAB_CLASS_NAME = 'AdminMyModuleController';

    public function __construct(
        private readonly DataService $dataService, // Injecting a service for demonstration
    ) {}

    
    /**
     * @Route("/mymodule/configure", name="mymodule_admin_configure", methods={"GET", "POST"})
     */
    public function configure(Request $request): Response
    {
        $form = $this->createForm(TrialKitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dump($form->getData());
            $this->dataService->getData(); // Example of using the injected service
        }
        
        return $this->render('@Modules/mymodule/views/templates/admin/configure.html.twig', [
            'some_variable' => 'Hello from Symfony!',
            'form' => $form->createView(),
        ]);
    }
}
