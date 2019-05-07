<?php


namespace Model;
class ArticleManager extends AbstractManager
{
    const TABLE = 'article';
    public function __construct(\PDO $pdo)
    {
        parent::__construct(self::TABLE, $pdo);
    }

    //create a new article
    public function insert(Article $article): int
    {
        $statement = $this->pdo->prepare("INSERT INTO $this->table (date, title, content, picture, miniature, user_id, category_id) VALUES ( NOW(), :title, :content, :picture, :miniature, :user_id, :category)");
        $statement->bindValue(':title', $article->getTitle(), \PDO::PARAM_STR);
        $statement->bindValue(':content', $article->getContent(),\PDO::PARAM_STR);
        $statement->bindValue(':picture', $article->getPicture(), \PDO::PARAM_STR);
        $statement->bindValue(':miniature', $article->getMiniature(), \PDO::PARAM_STR);
        $statement->bindValue(':user_id', $article->getUserId(), \PDO::PARAM_STR);
        $statement->bindValue(':category', $article->getCategoryId(), \PDO::PARAM_STR);


        if ($statement->execute()) {
//            var_dump($statement);
            return $this->pdo->lastInsertId();
        }
    }

    // show all articles on index user
    public function selectAllArticles(): array
    {
        $this->pdo->query("SET lc_time_names = 'fr_FR'");
        return $this->pdo->query('SELECT article.id, DATE_FORMAT(article.date, "%e %M %Y à %Hh %i") AS date , article.title, article.content, article.picture, article.miniature, user.firstname AS userFirstname, user.lastname AS userLastname, category.name AS categoryName, article.modified FROM article INNER JOIN user ON article.user_id =user.id INNER JOIN category ON article.category_id=category.id ORDER BY date DESC', \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }

    public function selectArticlesForIndex(): array
    {
        return $this->pdo->query('SELECT * FROM article ORDER BY date DESC LIMIT 3', \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }

  // delete article by id
    public function delete(int $id): int
    {
        $statement = $this->pdo->prepare("DELETE FROM article WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    // update article and picture
    public function update(Article $article): int
    {
        $statement = $this->pdo->prepare("UPDATE $this->table SET title = :title, content = :content, picture = :picture, miniature = :miniature, modified = NOW() WHERE id=:id");
        $statement->bindValue('title', $article->getTitle(), \PDO::PARAM_STR);
        $statement->bindValue('content', $article->getContent(), \PDO::PARAM_STR);
        $statement->bindValue('id', $article->getId(), \PDO::PARAM_INT);
        $statement->bindValue('picture', $article->getPicture(), \PDO::PARAM_STR);
        $statement->bindValue('miniature', $article->getMiniature(), \PDO::PARAM_STR);
        return $statement->execute();

    }
    public function count()
    {
        $numbers = $this->pdo->query('SELECT COUNT(title) AS Numbers FROM article ')->fetchColumn();
        return $numbers;
    }

    public function selectAllArticlesAndCategory(): array
    {
        return $this->pdo->query('SELECT article.id, article.title, category.name, article.content, article.:miniature FROM article INNER JOIN category ON category.id = article.user_id;;', \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }

    public function selectCategory(){
        return $this->pdo->query('SELECT id, name FROM category', \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }

    public function selectArticlesByCategory(int $id): array
    {
        $this->pdo->query("SET lc_time_names = 'fr_FR'");
        return $this->pdo->query("SELECT article.id, DATE_FORMAT(article.date, \"%e %M %Y à %Hh %i\") AS date, article.title, category.name, article.content, article.miniature, user.firstname AS userFirstname, user.lastname AS userLastname FROM article INNER JOIN user ON article.user_id =user.id INNER JOIN category ON category.id = article.category_id WHERE category_id= $id;", \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }

    public function selectOneArticleById(int $id)
    {
        // prepared request
        $this->pdo->query("SET lc_time_names = 'fr_FR'");
        $statement = $this->pdo->prepare("SELECT article.id, DATE_FORMAT(article.date, \"%e %M %Y à %Hh %i\") AS date, article.title, article.content, article.picture, user.firstname AS userFirstname, user.lastname AS userLastname FROM article INNER JOIN user ON article.user_id =user.id AND article.id=:id ORDER BY date DESC");
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->className);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function selectArticleByUser($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE user_id=:id ORDER BY date DESC");
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->className);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}