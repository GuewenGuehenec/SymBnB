<?php


namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class PaginationService
{
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $twig;
    private $route;
    private $templatePath;

    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $request, $templatePath)
    {
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager = $manager;
        $this->twig = $twig;
        $this->templatePath = $templatePath;
    }

    public function display()
    {
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }

    public function getPages()
    {
        if (empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devous paginer !
             Utilisez la méthode setEntityClass() de votre objet PaginationService !");
        }
        // 1) Connaître le total des enregistrements de la table
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        // 2) Faire la division, l'arrondi et le renvoyer
        return ceil($total / $this->limit);
    }

    public function getData()
    {
        if (empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devous paginer !
             Utilisez la méthode setEntityClass() de votre objet PaginationService !");
        }
        // 1) Calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        // 2) Demander au repository de trouver les éléments et retourner les données
        $repo = $this->manager->getRepository($this->entityClass);
        return $repo->findBy([], [], $this->limit, $offset);
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

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): PaginationService
    {
        $this->limit = $limit;
        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $currentPage): PaginationService
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
        return $this;
    }
}