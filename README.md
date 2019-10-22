# FractalBundle
Fractal bundle for Symfony 3/4

## Installation

### Step 1: Composer require

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
    
##### Symfony 4:

    <?php
        // config/bundles.php
        
        return [
            // ...
            Dmytrof\FractalBundle\DmytrofFractalBundle::class => ['all' => true],
        ];
        
        
## Usage