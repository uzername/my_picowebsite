<?php

/**
 * 
 * Jovan's Pico Security plugin. Place tokens in YAML and limit access to pages.
 * This is the main file of plugin. it is used to dispatch reference to secured page, intercept the query 
 * and render the propper sequrity message with input line for token entry. It also processes the received tokens.
 * It is being rendered every time we access the page.
 * Some pages should not be viewed by everyone...
 *
 * @author  Daniel Rudolf and Jovan
 * @link    http://picocms.org
 * @license http://opensource.org/licenses/MIT The MIT License
 * @version 1.0
 */
final class TokenSecurity extends AbstractPicoPlugin
{
    /**
     * This plugin is enabled by default?
     *
     * @see AbstractPicoPlugin::$enabled
     * @var boolean
     */
    protected $enabled = false;
    
    /**
     * This plugin depends on ...
     *
     * @see AbstractPicoPlugin::$dependsOn
     * @var string[]
     */
    protected $dependsOn = array();

    public $TokenSecurityQueryFormPath = "";
    private $TokenSecurityPageData=array();

    public function __construct(Pico $instPico)
    {
        parent::__construct($instPico);
        $this->TokenSecurityQueryFormPath = __DIR__."/"."queryform";
    }
    /**
     * Triggered after Pico has loaded all available plugins
     *
     * This event is triggered nevertheless the plugin is enabled or not.
     * It is NOT guaranteed that plugin dependencies are fulfilled!
     *
     * @see    Pico::getPlugin()
     * @see    Pico::getPlugins()
     * @param  object[] &$plugins loaded plugin instances
     * @return void
     */
    public function onPluginsLoaded(array &$plugins)
    {
        // your code
    }

    /**
     * Triggered after Pico has read its configuration
     *
     * @see    Pico::getConfig()
     * @param  array &$config array of config variables
     * @return void
     */
    public function onConfigLoaded(array &$config)
    {
        // your code
    }

    /**
     * Triggered after Pico has evaluated the request URL
     *
     * @see    Pico::getRequestUrl()
     * @param  string &$url part of the URL describing the requested contents
     * @return void
     */
    public function onRequestUrl(&$url)
    {
        // your code
    }

    /**
     * Triggered after Pico has discovered the content file to serve
     *
     * @see    Pico::getBaseUrl()
     * @see    Pico::getRequestFile()
     * @param  string &$file absolute path to the content file to serve
     * @return void
     */
    public function onRequestFile(&$file)
    {
        // your code
    }

    /**
     * Triggered before Pico reads the contents of the file to serve
     *
     * @see    Pico::loadFileContent()
     * @see    DummyPlugin::onContentLoaded()
     * @param  string &$file path to the file which contents will be read
     * @return void
     */
    public function onContentLoading(&$file)
    {
        // your code
        //error_log(__FILE__.date(' Y-m-d H:i:s')." : "."onContentLoading"."\n", 3, __DIR__."/"."debug.log");
        //error_log(__FILE__.date(' Y-m-d H:i:s')." : ".print_r($file,true)."\n", 3, __DIR__."/"."debug.log");

    }

    /**
     * Triggered after Pico has read the contents of the file to serve
     *
     * @see    Pico::getRawContent()
     * @param  string &$rawContent raw file contents
     * @return void
     */
    public function onContentLoaded(&$rawContent)
    {
        // your code
    }

    /**
     * Triggered before Pico reads the contents of a 404 file
     *
     * @see    Pico::load404Content()
     * @see    DummyPlugin::on404ContentLoaded()
     * @param  string &$file path to the file which contents were requested
     * @return void
     */
    public function on404ContentLoading(&$file)
    {
        // your code
    }

    /**
     * Triggered after Pico has read the contents of the 404 file
     *
     * @see    Pico::getRawContent()
     * @param  string &$rawContent raw file contents
     * @return void
     */
    public function on404ContentLoaded(&$rawContent)
    {
        // your code
    }

    /**
     * Triggered when Pico reads its known meta header fields
     *
     * @see    Pico::getMetaHeaders()
     * @param  string[] &$headers list of known meta header
     *     fields; the array value specifies the YAML key to search for, the
     *     array key is later used to access the found value
     * @return void
     */
    public function onMetaHeaders(array &$headers)
    {
        // your code

    }

    /**
     * Triggered before Pico parses the meta header
     *
     * @see    Pico::parseFileMeta()
     * @see    DummyPlugin::onMetaParsed()
     * @param  string   &$rawContent raw file contents
     * @param  string[] &$headers    known meta header fields
     * @return void
     */
    public function onMetaParsing(&$rawContent, array &$headers)
    {
        // your code
    }

    /**
     * Triggered after Pico has parsed the meta header
     *
     * @see    Pico::getFileMeta()
     * @param  string[] &$meta parsed meta data
     * @return void
     */
    public function onMetaParsed(array &$meta)
    {
        // your code
        //error_log(__FILE__.date(' Y-m-d H:i:s')." : "."onMetaParsed"."\n", 3, __DIR__."/"."debug.log");
        //error_log(__FILE__.date(' Y-m-d H:i:s')." : ".print_r($meta, true)."\n", 3, __DIR__."/"."debug.log");
        //here comes all the processing of access
        if (array_key_exists('usetoken', $meta) && (array_key_exists('tokenpath', $meta)) && ($meta['tokenpath']!="") && ($meta['usetoken']==1)) {
            //error_log(__FILE__.date(' Y-m-d H:i:s')." : "."UseToken found"."\n", 3, __DIR__."/"."debug.log");
            $this->TokenSecurityPageData['UseToken'] = 'TRUE';
            //Has the form data being sent?
            if ( (array_key_exists('retaddr', $_POST))&&(array_key_exists('accesstoken', $_POST)) ) {
                //error_log(__FILE__.date(' Y-m-d H:i:s')." : ".print_r($_POST,true)."\n", 3, __DIR__."/"."debug.log");
                //check here the token. It has been written in plaintext form field
                $TheToken = strval(($_POST['accesstoken']));
                $this->TokenSecurityPageData['accesgranted']='FALSE';
                //getting info about required token

                $string = file_get_contents(__DIR__."/"."tokenlib.json");
                $json_a = json_decode($string, true);
                //error_log(__FILE__.date(' Y-m-d H:i:s')." : "."TOKEN PROCESSING: ".print_r($json_a,true)."\n", 3, __DIR__."/"."debug.log");
                if (array_key_exists($meta['tokenpath'], $json_a['tokens'])) {
                    //error_log(__FILE__.date(' Y-m-d H:i:s')." : "."TOKEN PROCESSING: "."Token Matched in token lib. Settings are correct"."\n", 3, __DIR__."/"."debug.log");
                    if (($json_a['tokens'][$meta['tokenpath']]) == md5($TheToken) ) { //token found in library
                        //error_log(__FILE__.date(' Y-m-d H:i:s')." : "."TOKEN PROCESSING: "."Token Found. Now should display the content"."\n", 3, __DIR__."/"."debug.log");
                        session_start(); //write param to session
                        $this->TokenSecurityPageData['accesgranted']='TRUE';
                        if (!isset($_SESSION['visited_secured_pages'])) {
                            $_SESSION['visited_secured_pages']= array();
                        }
                        if (in_array($_POST['retaddr'], $_SESSION['visited_secured_pages']) == false) {
                            $_SESSION['visited_secured_pages'][] = $_POST['retaddr'];
                        }
                        header("Location: "."?".$_POST['retaddr']);
                        exit;
                    }
                }
            }
        }
    }

    /**
     * Triggered before Pico parses the pages content
     *
     * @see    Pico::prepareFileContent()
     * @see    DummyPlugin::prepareFileContent()
     * @see    DummyPlugin::onContentParsed()
     * @param  string &$rawContent raw file contents
     * @return void
     */
    public function onContentParsing(&$rawContent)
    {
        // your code
        //error_log(__FILE__.date(' Y-m-d H:i:s')." : "."onContentParsing"."\n", 3, __DIR__."/"."debug.log");
        //error_log(__FILE__.date(' Y-m-d H:i:s')." : ".print_r($rawContent, true)."\n", 3, __DIR__."/"."debug.log");

    }

    /**
     * Triggered after Pico has prepared the raw file contents for parsing
     *
     * @see    Pico::parseFileContent()
     * @see    DummyPlugin::onContentParsed()
     * @param  string &$content prepared file contents for parsing
     * @return void
     */
    public function onContentPrepared(&$content)
    {
        // your code
        session_start();
        error_log(__FILE__.date(' Y-m-d H:i:s')." : "."onContentPrepared"."\n", 3, __DIR__."/"."debug.log");
        //error_log(__FILE__.date(' Y-m-d H:i:s')." : ".print_r($content, true)."\n", 3, __DIR__."/"."debug.log");
        error_log(__FILE__.date(' Y-m-d H:i:s')." : ".$this->TokenSecurityQueryFormPath."\n", 3, __DIR__."/"."debug.log");        
        $checkSession = (session_status() == PHP_SESSION_ACTIVE)&&(array_key_exists('visited_secured_pages', $_SESSION) ) && (in_array($this->getPico()->getRequestUrl(), $_SESSION['visited_secured_pages']));
        error_log(__FILE__.date(' Y-m-d H:i:s')." : "."TOKEN PROCESSING: "."session data"."\n", 3, __DIR__."/"."debug.log");
        error_log(__FILE__.date(' Y-m-d H:i:s')." : "."TOKEN PROCESSING: "."session_status:".print_r((session_status() == PHP_SESSION_ACTIVE),true)."\n", 3, __DIR__."/"."debug.log");
        error_log(__FILE__.date(' Y-m-d H:i:s')." : "."TOKEN PROCESSING: "."session content:".print_r($_SESSION,true)."\n", 3, __DIR__."/"."debug.log");
        if ((array_key_exists('UseToken', $this->TokenSecurityPageData))&&($this->TokenSecurityPageData['UseToken'] == 'TRUE')) { //probably better use Pico::getFileMeta()
            if ($checkSession == false) {
                //render here the token entry form
                $processAddr="";
                $renderPartial="<form action=\"".""."\" method=\"post\">";
                $renderPartial.=file_get_contents($this->TokenSecurityQueryFormPath);
                $renderPartial.="<input type=\"hidden\" name=\"retaddr\" value=\"".$this->getPico()->getRequestUrl()."\" />";
                $renderPartial.="</form>";
                $content = $renderPartial;
            }
        }                
    }

    /**
     * Triggered after Pico has parsed the contents of the file to serve
     *
     * @see    Pico::getFileContent()
     * @param  string &$content parsed contents
     * @return void
     */
    public function onContentParsed(&$content)
    {
        // your code
    }

    /**
     * Triggered before Pico reads all known pages
     *
     * @see    Pico::readPages()
     * @see    DummyPlugin::onSinglePageLoaded()
     * @see    DummyPlugin::onPagesLoaded()
     * @return void
     */
    public function onPagesLoading()
    {
        // your code
    }

    /**
     * Triggered when Pico reads a single page from the list of all known pages
     *
     * The `$pageData` parameter consists of the following values:
     *
     * | Array key      | Type   | Description                              |
     * | -------------- | ------ | ---------------------------------------- |
     * | id             | string | relative path to the content file        |
     * | url            | string | URL to the page                          |
     * | title          | string | title of the page (YAML header)          |
     * | description    | string | description of the page (YAML header)    |
     * | author         | string | author of the page (YAML header)         |
     * | time           | string | timestamp derived from the Date header   |
     * | date           | string | date of the page (YAML header)           |
     * | date_formatted | string | formatted date of the page               |
     * | raw_content    | string | raw, not yet parsed contents of the page |
     * | meta           | string | parsed meta data of the page             |
     *
     * @see    DummyPlugin::onPagesLoaded()
     * @param  array &$pageData data of the loaded page
     * @return void
     */
    public function onSinglePageLoaded(array &$pageData)
    {
        // your code
    }

    /**
     * Triggered after Pico has read all known pages
     *
     * See {@link DummyPlugin::onSinglePageLoaded()} for details about the
     * structure of the page data.
     *
     * @see    Pico::getPages()
     * @see    Pico::getCurrentPage()
     * @see    Pico::getPreviousPage()
     * @see    Pico::getNextPage()
     * @param  array[]    &$pages        data of all known pages
     * @param  array|null &$currentPage  data of the page being served
     * @param  array|null &$previousPage data of the previous page
     * @param  array|null &$nextPage     data of the next page
     * @return void
     */
    public function onPagesLoaded(
        array &$pages,
        array &$currentPage = null,
        array &$previousPage = null,
        array &$nextPage = null
    ) {
        // your code
    }

    /**
     * Triggered before Pico registers the twig template engine
     *
     * @return void
     */
    public function onTwigRegistration()
    {
        // your code
        
    }

    /**
     * Triggered before Pico renders the page
     *
     * @see    Pico::getTwig()
     * @see    DummyPlugin::onPageRendered()
     * @param  Twig_Environment &$twig          twig template engine
     * @param  array            &$twigVariables template variables
     * @param  string           &$templateName  file name of the template
     * @return void
     */
    public function onPageRendering(Twig_Environment &$twig, array &$twigVariables, &$templateName)
    {
        // your code
        //Appears that Twig_Environment &$twig object contains all the website content. Yikes!
        //error_log(__FILE__.date(' Y-m-d H:i:s')." : "."on Page Rendering! \n", 3, __DIR__."/"."debug.log");
        //error_log(__FILE__.date(' Y-m-d H:i:s')." : ".print_r($twig,true)."\n", 3, __DIR__."/"."debug.log");
        //error_log(__FILE__.date(' Y-m-d H:i:s')." : ".print_r($twigVariables,true)."\n", 3, __DIR__."/"."debug.log");
        //error_log(__FILE__.date(' Y-m-d H:i:s')." : ".print_r($templateName,true)."\n", 3, __DIR__."/"."debug.log");
    }

    /**
     * Triggered after Pico has rendered the page
     *
     * @param  string &$output contents which will be sent to the user
     * @return void
     */
    public function onPageRendered(&$output)
    {
        // your code
        //error_log(__FILE__.date(' Y-m-d H:i:s')." : "."on Page Rendered! \n", 3, __DIR__."/"."debug.log");

    }
}
