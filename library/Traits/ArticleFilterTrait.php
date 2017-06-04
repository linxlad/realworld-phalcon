<?php

namespace RealWorld\Traits;

use function count;
use Phalcon\Http\Request;
use Phalcon\Mvc\Model\Query\Builder as QueryBuilder;
use RealWorld\Models\Articles;
use RealWorld\Models\ArticleTag;
use RealWorld\Models\Favorites;
use RealWorld\Models\Tags;
use RealWorld\Models\User;
use function str_replace;

/**
 * Trait ArticleFilterTrait
 * @package RealWorld\Traits
 * @property Request request
 */
trait ArticleFilterTrait
{
    /**
     * @return mixed
     */
    public function filterArticles()
    {
        $queryBuilder = $this->getQueryBuilder();
        $query = null;

        if ($article = Articles::findFirstBySlug($this->getSlugOrFalse())) {
            return $article;
        } elseif ($author = $this->getQueryByKeyOrFalse('author')) {
            // Articles by author.
            $query = $this->getArticlesByAuthor($author);
        } elseif ($favorited = $this->getQueryByKeyOrFalse('favorited')) {
            // Articles favourited by username.
            $query = $this->getArticlesFavouritedByUsername($favorited);
        } elseif ($tag = $this->getQueryByKeyOrFalse('tag')) {
            // Articles by tag.
            $query = $this->getArticlesByTag($tag);
        }

        if ($this->getQueryByKeyOrFalse('limit') && $this->getQueryByKeyOrFalse('offset')) {
            $queryBuilder->limit($query['limit'], $query['offset']);
        }

        $articles = ($query ?? $queryBuilder)
            ->orderBy('a.id')
            ->getQuery()
            ->execute();

        return $articles;
    }

    /**
     * @param $author
     * @return QueryBuilder
     */
    protected function getArticlesByAuthor($author)
    {
        return $this->getQueryBuilder()
            ->leftJoin(User::class, 'u.id = a.userId', 'u')
            ->where('u.username = :username:', [
                'username' => $author
            ]);
    }

    /**
     * @param $favorited
     * @return QueryBuilder
     */
    protected function getArticlesFavouritedByUsername($favorited)
    {
        return $this->getQueryBuilder()
            ->leftJoin(Favorites::class, 'f.articleId = a.id', 'f')
            ->leftJoin(User::class, 'u.id = f.userId', 'u')
            ->where('u.username = :username:', [
                'username' => $favorited
            ]);
    }

    /**
     * @param $tag
     * @return QueryBuilder
     */
    protected function getArticlesByTag(string $tag)
    {
        return $this->getQueryBuilder()
            ->leftJoin(ArticleTag::class, 'att.articleId = a.id', 'att')
            ->leftJoin(Tags::class, 't.id = att.tagId', 't')
            ->where('t.name = :name:', [
                'name' => $tag
            ]);
    }

    /**
     * @return QueryBuilder
     */
    private function getQueryBuilder()
    {
        return (new QueryBuilder())->from(['a'  => Articles::class]);
    }

    /**
     * @return bool|mixed
     */
    private function getSlugOrFalse()
    {
        $params = $this->request->getQuery();

        if (
            count($params) === 1 &&
            ($url = $this->getQueryByKeyOrFalse('_url'))
        ) {
            return str_replace('/api/articles/', '', $url);
        }

        return false;
    }

    /**
     * @param string $key
     * @return mixed
     */
    private function getQueryByKeyOrFalse($key)
    {
        return $this->request->getQuery($key) ?? false;
    }
}