<?php

namespace Controller;

use Model\AdminCommentManager;
use Model\Comment;

class AdminCommentController extends AbstractController
{

    public function add(int $articleId)
    {
        $errorConnexion ='';
        if (isset($_SESSION['user']) && (!empty($_POST))) {
                $CommentManager = new AdminCommentManager($this->getPdo());
                $comment = new Comment();
                $comment->setContent($_POST['content']);
                $comment->setArticleId($articleId);
                $comment->setUserId($_SESSION['user']['id']);
                $id = $CommentManager->insert($comment);
                header('Location: /article/' . $articleId);
        }else{
            $errorConnexion = 'Vous devez être connecté pour commenter cet article.';
            $return = $_SERVER['HTTP_REFERER'];
            return $this->twig->render('Article/logToComment.html.twig', ['errorConnexion' => $errorConnexion, 'return' => $return]);
            // TODO redirection on last visited page after connexion
        }
    }

    public function indexAdminComments()
    {
        $commentsManager = new AdminCommentManager($this->getPdo());
        $comments = $commentsManager->selectAllComments();
        $signals = $commentsManager->countSignal();
        $active = "comments";
        return $this->twig->render('Admin/AdminComment/indexAdminComment.html.twig', [
            'comments' => $comments,
            "signals" => $signals,
            "active" => $active
        ]);
    }

//    Index de tout les commentaires signalés
    public function indexAdminCommentsSignals()
    {
        $commentsSignals = new AdminCommentManager($this->getPdo());
        $shows = $commentsSignals->showSignal();
        $count = $commentsSignals->countSignal();
        return $this->twig->render('Admin/AdminComment/showCommentSignal.html.twig', [
            'comments' => $shows,
            "signals" => $count,
        ]);
    }

//  Aucun changement ici, la méthode reste la même que le commentaire soit signalé ou pas
    public function delete(int $id)
    {
        $commentManager = new AdminCommentManager($this->getPdo());
        $commentManager->delete($id);
    }

//  Pour ajouter un signalement à un commentaire précis, il est incrémentiel
    public function addCommentSignal($id)
    {
        $commentSignal = new AdminCommentManager($this->getPdo());
        $commentSignal->addSignal($id);
    }

//  Reset les signalements dans le cas ou cela n'est pas justifié
    public function resetSignal($id)
    {
        $commentSignal = new AdminCommentManager($this->getPdo());
        $commentSignal->resetSignal($id);
    }

}
