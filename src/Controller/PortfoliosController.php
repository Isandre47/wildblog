<?php

namespace Controller;


use Model\ArticleManager;

class PortfoliosController extends AbstractController
{
    public function groupe1()
    {
        $toto = new ArticleManager($this->getPdo());
        $category = $toto->selectCategory();
        return $this->twig->render('Portfolios/groupe1/page1.html.twig', [ "session" => $_SESSION, 'category' => $category, 'isLogged' => $this->isLogged(),]);
    }

    public function groupe2()
    {
        $toto = new ArticleManager($this->getPdo());
        $category = $toto->selectCategory();
        return $this->twig->render('Portfolios/groupe2/page2.html.twig', [ "session" => $_SESSION, 'category' => $category, 'isLogged' => $this->isLogged(),]);
    }

    public function groupe3()
    {
        $toto = new ArticleManager($this->getPdo());
        $category = $toto->selectCategory();
        return $this->twig->render('Portfolios/groupe3/page3.html.twig', ["session" => $_SESSION, 'category' => $category, 'isLogged' => $this->isLogged(),]);
    }

    public function groupe4()
    {
        $toto = new ArticleManager($this->getPdo());
        $category = $toto->selectCategory();
        return $this->twig->render('Portfolios/groupe4/page4.html.twig', ["session" => $_SESSION, 'category' => $category, 'isLogged' => $this->isLogged(),]);
    }

    public function groupe5()
    {
        $toto = new ArticleManager($this->getPdo());
        $category = $toto->selectCategory();
        return $this->twig->render('Portfolios/groupe5/page5.html.twig', ["session" => $_SESSION, 'category' => $category, 'isLogged' => $this->isLogged(),]);
    }

    public function groupe6()
    {
        $toto = new ArticleManager($this->getPdo());
        $category = $toto->selectCategory();
        return $this->twig->render('Portfolios/groupe6/page6.html.twig', ["session" => $_SESSION, 'category' => $category, 'isLogged' => $this->isLogged(),]);
    }

    public function groupe7()
    {
        $toto = new ArticleManager($this->getPdo());
        $category = $toto->selectCategory();
        return $this->twig->render('Portfolios/groupe7/page7.html.twig', ["session" => $_SESSION, 'category' => $category, 'isLogged' => $this->isLogged(),]);
    }

    public function groupe8()
    {
        $toto = new ArticleManager($this->getPdo());
        $category = $toto->selectCategory();
        return $this->twig->render('Portfolios/groupe8/page8.html.twig', ["session" => $_SESSION, 'category' => $category, 'isLogged' => $this->isLogged(),]);
    }

    public function groupe9()
    {
        $toto = new ArticleManager($this->getPdo());
        $category = $toto->selectCategory();
        return $this->twig->render('Portfolios/groupe9/page9.html.twig', ["session" => $_SESSION, 'category' => $category, 'isLogged' => $this->isLogged(),]);
    }

}
