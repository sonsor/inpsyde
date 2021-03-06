<?php
namespace Sonsor\Inpsyde;

use Sonsor\Inpsyde\Interfaces\IPlugin;

class Plugin implements IPlugin
{

    /**
     *
     */
    const URI_PARAM = 'inpsyde';

    /**
     * @param string $hook
     * @param $func
     */
    public function action(string $hook, $func): void
    {
        add_action($hook, $func,);
    }

    /**
     * @param string $template
     * @return string
     */
    public function getTemplate( string $template ): string
    {
        return INPSYDE_TEMPLATE_DIRECTORY . DS . $template . '.php';
    }

    /**
     *
     */
    public function init(): void
    {
        add_rewrite_rule(
            self::URI_PARAM,
            'index.php?' . self::URI_PARAM . '=true',
            'top'
        );
    }

    /**
     * @param array $variable
     */
    public function variables( array $variables): array
    {
        $variables[] = self::URI_PARAM;
        return $variables;
    }

    /**
     * @param string $template
     * @return string
     */
    public function template( string $template ): string
    {
        if ( (bool) get_query_var( self::URI_PARAM ) !== true) {
            return $template;
        }

        return $this->getTemplate('index');
    }

    /**
     *
     */
    public function hook(): void
    {
        $this->action(
            'init',
            array($this, 'init')
        );

        $this->action(
            'query_vars',
            array($this, 'variables')
        );

        $this->action(
            'template_include',
            array($this, 'template')
        );
    }

    /**
     *
     */
    public static function main(): void
    {
        $inpsyde = new Plugin();
        $inpsyde->hook();
    }
}
