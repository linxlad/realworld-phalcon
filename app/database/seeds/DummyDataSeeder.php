<?php

use Yarak\DB\Seeders\Seeder;

use RealWorld\Models\Articles;
use RealWorld\Models\ArticleTag;
use RealWorld\Models\Comments;
use RealWorld\Models\Model;
use RealWorld\Models\Tags;
use RealWorld\Models\User;

/**
 * Class DummyDataSeeder
 */
class DummyDataSeeder extends Seeder
{
    /**
     * Total number of users.
     *
     * @var int
     */
    protected $totalUsers = 25;

    /**
     * Total number of tags.
     *
     * @var int
     */
    protected $totalTags = 10;

    /**
     * Percentage of users with articles.
     *
     * @var float Value should be between 0 - 1.0
     */
    protected $userWithArticleRatio = 0.8;

    /**
     * Maximum articles that can be created by a user.
     *
     * @var int
     */
    protected $maxArticlesByUser = 15;

    /**
     * Maximum tags that can be attached to an article.
     *
     * @var int
     */
    protected $maxArticleTags = 3;

    /**
     * Maximum number of comments that can be added to an article.
     *
     * @var int
     */
    protected $maxCommentsInArticle = 10;

    /**
     * Percentage of users with favorites.
     *
     * @var float Value should be between 0 - 1.0
     */
    protected $usersWithFavoritesRatio = 0.75;

    /**
     * Percentage of users with following.
     *
     * @var float Value should be between 0 - 1.0
     */
    protected $usersWithFollowingRatio = 0.75;

    /**
     * Populate the database with dummy data for testing.
     * Complete dummy data generation including relationships.
     * Set the property values as required before running database seeder.
     */
    public function run()
    {
        try {
            $faker = Faker\Factory::create();
            $users = factory(User::class)->times($this->totalUsers)->create();
            $userWithArticles = Model::random($users, (int)$this->totalUsers * $this->userWithArticleRatio);

            foreach ($userWithArticles as $user) {
                $articlesPerUser = range(0, $faker->numberBetween(1, $this->maxArticlesByUser));

                foreach ($articlesPerUser as $n) {
                    $user = Model::random($userWithArticles, 1);
                    $article = factory(Articles::class)->create([
                        'userId' => reset($user)->id
                    ]);

                    $tagsPerArticle = range(0, $faker->numberBetween(1, min($this->maxArticleTags, $this->totalTags)));

                    foreach ($tagsPerArticle as $n) {
                        $tag = factory(Tags::class)->create();
                        factory(ArticleTag::class)->create([
                            'articleId' => $article->id,
                            'tagId' => $tag->id,
                        ]);
                    }

                    $commentsPerArticle = range(0, $faker->numberBetween(1, $this->maxCommentsInArticle));

                    foreach ($commentsPerArticle as $n) {
                        $randomUser = Model::random($users, 1);
                        factory(Comments::class)->create([
                            'body' => $faker->paragraph($faker->numberBetween(1, 5)),
                            'userId' => reset($randomUser)->id,
                            'articleId' => $articles->id,
                        ]);
                    }
                }
            }

            $articles = Articles::find()->filter(function ($child) {
                return $child;
            });
            $usersWithFavorites = Model::random($userWithArticles, (int)count($userWithArticles) * $this->usersWithFavoritesRatio);

            foreach ($usersWithFavorites as $user) {
                $randomArticles = Model::random($articles, (int)count($users) * $this->usersWithFavoritesRatio);
                foreach ($randomArticles as $article) {
                    $user->favorite($article);
                }
            }

            $usersWithFollowing = Model::random($users, (int)count($users) * $this->usersWithFollowingRatio);

            foreach ($usersWithFollowing as $follower) {
                $userToFollow = Model::random($users, $faker->numberBetween(1, (int)(count($users) - 1) * 0.2));

                foreach ($userToFollow as $user) {
                    $follower->follow($user);
                }
            }
        } catch (\Exception $e) {
            var_dump($e);
        }
    }
}
