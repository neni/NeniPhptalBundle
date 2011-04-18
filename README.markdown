NeniPhptalBundle
================

Renderer TAL in Symfony2 with [PHPTAL](http://phptal.org/).



## Installation



### 1. Add this bundle to your project

        $ git clone git://github.com/neni/NeniPhptalBundle.git vendor/bundles/Neni/PhptalBundle

or it as submodule

        $ git submodule add git://github.com/neni/NeniPhptalBundle.git vendor/bundles/Neni/PhptalBundle
        $ git submodule update --init
        

### 2. Install PHPTal

if PHPTal is not installed

        $ git svn clone https://svn.motion-twin.com/phptal/trunk vendor/Phptal-svn
(if you using git for your project, ignore "vendor/Phptal-svn/*")

and add set_include_path in file "app/autoload.php"

       set_include_path(get_include_path().':'.__DIR__. '/../vendor/Phptal-svn/classes/');

it is not possible to add a SVN repository as submodule (or I do not know how to make this).


### 3. Add the bundle to your application kernel:

add in file "app/autoload.php"

        $loader->registerNamespaces(array(
             // if use of Sensio's FrameworkExtraBundle (with @extra:Tal)
             'Sensio' => array(
                            __DIR__.'/../vendor/bundles',
                            __DIR__.'/../vendor/bundles/Neni/PhptalBundle/Local'
                       ),
            
             // ...
        '    Neni' => __DIR__.'/../vendor/bundles',
             // ...
        ));

add in file "app/AppKernel.php"

        public function registerBundles()
        {
            return array(
                // ...
                new Neni\PhptalBundle\NeniPhptalBundle(),
                // ...
            );
        }


change in the configuration file ("app/config/config.yml")

       framework:
             # ...
             templating: { engines: ['phptal', 'twig', 'php'] }
             #...


       # Options 
       neni_phptal: ~ 
             #charset:        "%kernel.charset%"           # encodage
             #output_mode: 	"XHTML"                      # XHTML, XML or HTML5
             #cache_dir: 		"%kernel.cache_dir%/phptal"  # cache location
             #cache_lifetime: 30                           # cache life time in days
             #force_reparse:  false                        # force reparse (for debugging pre_filter)
             # Configuration for filters
             #pre_filter:
             #     remove_comment:    [ class:"Neni\PhptalBundle\Phptal\Filter\RemoveComment" ]
             #     remove_whitespace: [ class:"Neni\PhptalBundle\Phptal\Filter\RemovewhiteSpace" ]
             #     replace_toto_titi: [ class:"Neni\PhptalBundle\Phptal\Filter\ReplaceString", arguments:['toto','titi'] ]
             #post_filter:
             #     replace_tata_tutu: [ class:"Neni\PhptalBundle\Phptal\Filter\ReplaceString", arguments:['tata','tutu'] ]
             #     replace_tutu_tyty: [ class:"Neni\PhptalBundle\Phptal\Filter\ReplaceString", arguments:['tutu','tyty'] ]
             #annotation: true



## Usage

the template extension is '.tal' and you can call it in controllers like this

    return $this->render('HelloBundle:Hello:index.html.tal', array('name' => $name));

the default options can be change in controlers via parameters['_engine_']

    public function indexAction()
    {
        $engine = array();
        $engine['resolver'] = 'tal.resolver.orm';
        $engine['output_format'] = 'XML';
        return $this->render('NeniSiteBundle:test:index.xml.tal', array('test'=>'un test','_engine_'=>$engine) );
    }

if you use Sensio's FrameworkExtraBundle for annotation, use '@extra:Tal' in palce of '@extra:Template'  
(do not forget to add "annotation: true" to section neni_phptal in config file)


for helpers, use syntax php:Helper.get('helper_name').methode_name('parameters')

    <img tal:attributes="src php:Helper.get('assets').getUrl('bundles/test/img/logo.png')" src="../../public/img/logo.png" alt="logo" />





## To do

- verify annotation
- make tests suite
- improve documentation



