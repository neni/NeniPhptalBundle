<?php


namespace Neni\PhptalBundle\Phptal\Helper;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\Helper\Helper ;

class PhptalCssHelper extends Helper
{

    protected $container;
    protected $defaultCss;
    protected $site_path;

    static $metafiles = array();
    static $metadatas = array();

    static $perform = true;


    public function __construct(ContainerInterface $container, $options)
    {
        $this->container = $container;
        $this->site_path = $options['site_path'];
        $this->defaultCss = 'site/style/global.css';
    }


    public function addFile($file, $bloc=null, $position=null)
    {
        $bloc = ($bloc==null)?$this->defaultCss:$bloc;
        if(PhptalCssHelper::$perform){
            $key = $file;
            $position=($position==null)?'none':$position;
            if(!isset($this::$metafiles[$bloc])){$this::$metafiles[$bloc]=array();}
            if($position=="begin"){
                unset($this::$metafiles[$bloc][$key]);
                $this::$metafiles[$bloc] = array_merge(array($key=>$file), $this::$metafiles[$bloc]); 
            }elseif($position=="end"){
                unset($this::$metafiles[$bloc][$key]);
                $this::$metafiles[$bloc] = array_merge($this::$metafiles[$bloc], array($key=>$file)); 
            }else{
                $this::$metafiles[$bloc][$key] = $file;
            }
        }
        return $bloc;
    }


    public function getFile($file=null, $bloc=null, $position=null, $compute=false)
    {
        $bloc = ($bloc==null)?$this->defaultCss:$bloc;
        if($file!=null){
            $this->addFile($file, $bloc, $position);
        }
        if(($compute)&&(PhptalCssHelper::$perform)){
            // meta file

            // files
            $locator = $this->container->get('file_locator');
            $outputFile = $this->site_path .'/'. $bloc;
            $f = fopen($outputFile,'w');
            foreach($this::$metafiles[$bloc] as $fic){
                fwrite($f, file_get_contents($locator->locate($fic)) );
                //$tmp.=":".$locator->locate($fic);
            }
            fclose($f);
        }
        return $this->container->get('templating.helper.assets')->getUrl($bloc);
    }



    /*
    public function add($data, $bloc='global', $filltre=array(), $position="none")
    {
        $key = md5($data);
        if(PhptalCssHelper::perform){
            // todo: add filtres to transform data
            //
            //
            if($position=="begin"){
                unset($this::$metadatas[$block][$key]);
                $this::$metadatas[$block] = array_merge(array($key=>$data), $this::$metadatas[$block]); 
            }elseif($position=="end"){
                unset($this::$metadatas[$block][$key]);
                $this::$metadatas[$block] = array_merge($this::$metadatas[$block],array($key=>$data)); 
            }else{
                $this::$metadatas[$bloc][$key] = $data;
            }
        }
        return implode($this::$metadatas[$bloc][$key]);
    }

    public function addStart($bloc='global', $filltre=array(), $position="none")
    {
    }
    
    public function addStop($bloc='global', $filltre=array(), $position="none")
    {
        $data = '';
        return $this->add($data, $bloc='global', $filltre, $position);
    }
    */

    public function getName()
    {
        return 'talcss' ;
    }


}


