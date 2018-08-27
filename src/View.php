<?php
namespace Brave\TimerBoard;

/**
 * Simple view that renders a template.
 */
class View
{
    /**
     * @var string
     */
    private $template;

    /**
     * @var array
     */
    private $vars = [];

    /**
     * @param string $template
     * @throws \InvalidArgumentException if view is not a file or not readable.
     */
    public function __construct(string $template)
    {
        if (! is_file($template) || ! is_readable($template)) {
            throw new \InvalidArgumentException('Template is not readable or not a file.');
        }
        $this->template = $template;
    }

    /**
     * Set variables for the view.
     *
     * @param array $vars
     * @throws \InvalidArgumentException if the array contains a key "this"
     * @return self
     */
    public function setVars(array $vars)
    {
        if (array_key_exists('this', $vars)) {
            throw new \InvalidArgumentException('Key "this" cannot be used.');
        }
        $this->vars = $vars;

        return $this;
    }

    /**
     * Add a variable to the view.
     *
     * @param string $name
     * @param mixed $value
     * @throws \InvalidArgumentException if the name is "this"
     * @return self
     */
    public function addVar(string $name, $value)
    {
        if ($name === 'this') {
            throw new \InvalidArgumentException('name cannot be "this"');
        }
        $this->vars[$name] = $value;

        return $this;
    }

    /**
     * Escapes string with htmlspecialchars()
     *
     * @param string $str The unescaped string.
     * @return string
     */
    public function esc(string $str)
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Returns rendered template.
     *
     * @return string
     */
    public function getContent()
    {
        extract($this->vars);
        ob_start();
        include $this->template;
        return ob_get_clean();
    }
}
