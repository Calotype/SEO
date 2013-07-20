<?php namespace Calotype\SEO\Generators;

class RobotsGenerator
{
    protected $lines = array();

    public function generate()
    {
        return implode(PHP_EOL, $this->lines);
    }

    public function addSitemap($sitemap)
    {
        $this->addLine("Sitemap: $sitemap");
    }

    public function addUserAgent($user_agent)
    {
        $this->addLine("User-agent: $user_agent");
    }

    public function addHost($host)
    {
        $this->addLine("Host: $host");
    }

    public function addDisallow($directories)
    {
        $this->addRuleLine($directories, 'Disallow');
    }

    public function addAllow($directories)
    {
        $this->addRuleLine($directories, 'Allow');
    }

    protected function addRuleLine($directories, $rule)
    {
        foreach ((array) $directories as $directory) {
            $this->addLine("$rule: $directory");
        }
    }

    public function addComment($comment)
    {
        $this->addLine("# $comment");
    }

    public function addSpacer()
    {
        $this->addLine("");
    }

    public function addLine($line)
    {
        $this->lines[] = (string) $line;
    }

    public function addLines($lines)
    {
        foreach ((array) $lines as $line) {
            $this->addLine($line);
        }
    }
}
