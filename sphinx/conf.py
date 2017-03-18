import sphinx_rtd_theme, sys, os
from sphinx.highlighting import lexers
from pygments.lexers.web import PhpLexer

lexers['php'] = PhpLexer(startinline=True, linenos=1)
lexers['php-annotations'] = PhpLexer(startinline=True, linenos=1)
primary_domain = 'php'

html_theme = "sphinx_rtd_theme"
html_theme_path = [sphinx_rtd_theme.get_html_theme_path()]

extensions = []
master_doc = 'index'
project = u'Host Project'
copyright = u'2017, team neusta'
author = u'Thomas von Gostomski'
version = u'1.6.2'

html_title = "Host Documentation"
html_short_title = "Host"
