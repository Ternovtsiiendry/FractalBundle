DmytrofFractalBundle
====================

This bundle helps you to implement [Fractal by League](https://fractal.thephpleague.com) into 
your Symfony 3/4/5 application

## Installation

### Step 1: Install the bundle

    $ composer require dmytrof/fractal-bundle 
    
### Step 2: Enable the bundle

##### Symfony 3:
    
    <?php
    // app/AppKernel.php
    
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Dmytrof\FractalBundle\DmytrofFractalBundle(),
            // ...
        );
    }
    
##### Symfony 4/5:

    <?php
        // config/bundles.php
        
        return [
            // ...
            Dmytrof\FractalBundle\DmytrofFractalBundle::class => ['all' => true],
        ];
        
        
## Usage

Read the official Fractal [documentation](https://fractal.thephpleague.com/) before using this bundle

### 1.Basic usage


Imagine you have some models **Article**:
    
    <?php
    
    namespace App\Model;
    
    use App\Model\Author;
    
    class Article
    {
        /**
         * @var int
         */
        protected $id;
    
        /**
         * @var Author
         */
        protected $author;
    
        /**
         * @var string
         */
        protected $title;
    
        /**
         * @var string
         */
        protected $body;
    
        /**
         * @var \DateTime
         */
        protected $publishedAt;
    
        /**
         * Returns id
         * @return int|null
         */
        public function getId(): ?int
        {
            return $this->id;
        }
    
        /**
         * Sets id
         * @param int|null $id
         * @return $this
         */
        public function setId(?int $id): Article
        {
            $this->id = $id;
            return $this;
        }
    
        /**
         * Returns author
         * @return Author|null
         */
        public function getAuthor(): ?Author
        {
            return $this->author;
        }
    
        /**
         * Sets author
         * @param Author|null $author
         * @return Article
         */
        public function setAuthor(?Author $author): Article
        {
            $this->author = $author;
            return $this;
        }
    
        // ...
        // Other Setters and Getters
        // ...
    
        /**
         * Returns published at date
         * @return \DateTime|null
         */
        public function getPublishedAt(): ?\DateTime
        {
            return $this->publishedAt;
        }
    
        /**
         * Sets published at date
         * @param \DateTime|null $publishedAt
         * @return Article
         */
        public function setPublishedAt(?\DateTime $publishedAt): Article
        {
            $this->publishedAt = $publishedAt;
            return $this;
        }
    }
    
and **Author**:

    <?php
    
    namespace App\Model;
    
    class Author
    {
        /**
         * @var int
         */
        protected $id;
    
        /**
         * @var string
         */
        protected $firstName;
    
        /**
         * @var string
         */
        protected $lastName;
    
        /**
         * @var string
         */
        protected $email;
    
        /**
         * Returns id
         * @return int|null
         */
        public function getId(): ?int
        {
            return $this->id;
        }
    
        /**
         * Sets id
         * @param int|null $id
         * @return Author
         */
        public function setId(?int $id): Author
        {
            $this->id = $id;
            return $this;
        }
    
        // ...
        // Other Getters and Setters
        // ...
    }
    
##### Create transformers for your entities

AuthorTransformer:

    <?php
    
    namespace App\FractalTransformer;
    
    use App\Model\Author;
    use Dmytrof\FractalBundle\Transformer\AbstractTransformer;
    
    class AuthorTransformer extends AbstractTransformer
    {
        // Model which is handled by this transformer
        protected const SUBJECT_CLASS = Author::class;
    
        /**
         * @var bool
         */
        protected $showShortInfo = false;
    
        /**
         * @return bool
         */
        public function isShowShortInfo(): bool
        {
            return $this->showShortInfo;
        }
    
        /**
         * Sets show short info
         * @param bool $showShortInfo
         * @return AuthorTransformer
         */
        public function setShowShortInfo(bool $showShortInfo = true): AuthorTransformer
        {
            $this->showShortInfo = $showShortInfo;
            return $this;
        }
    
        /**
         * Transforms Author to array
         * @param Author $subject
         * @return array
         */
        public function transformSubject($subject): array
        {
            $data = [
                'id' => $subject->getId(),
                'firstName' => $subject->getFirstName(),
                'lastName' => $this->isShowShortInfo() ? substr($subject->getLastName(), 0, 1).'.' : $subject->getLastName(),
            ];
    
            if (!$this->isShowShortInfo()) {
                $data['email'] = $subject->getEmail();
            }
    
            return $data;
        }
    }

and ArticleTransformer:

    <?php
    
    namespace App\FractalTransformer;
    
    use App\Model\Article;
    use Dmytrof\FractalBundle\Transformer\AbstractTransformer;
    use League\Fractal\Resource\{Item, ResourceInterface};
    
    class ArticleTransformer extends AbstractTransformer
    {
        // Model which is handled by this transformer
        protected const SUBJECT_CLASS = Article::class;
    
        protected $defaultIncludes = [
            'author'
        ];
    
        protected $availableIncludes = [
            'body',
        ];
    
        /**
         * Transforms Article to array
         * @param Article $subject
         * @return array
         */
        public function transformSubject($subject): array
        {
            return [
                'id' => $subject->getId(),
                'title' => $subject->getTitle(),
                'publishedAt' => $this->transformDateTime($subject->getPublishedAt()),
            ];
        }
    
        /**
         * Includes author
         * @param Article $article
         * @return Item
         */
        public function includeAuthor(Article $article): Item
        {
            return $this->item($article->getAuthor(), AuthorTransformer::class);
        }
    
        /**
         * Includes body
         * @param Article $article
         * @return ResourceInterface
         */
        public function includeBody(Article $article): ResourceInterface
        {
            return $this->primitive($article->getBody());
        }
    }