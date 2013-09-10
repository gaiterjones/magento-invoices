## Magento Print/View Invoices
***

### Synopsis
This PHP application allows you to manage print and view Magento invoices with one click.

### Version
***
	@version		10092013
	@since			09 2013
	@author			gaiterjones
	@documentation	[blog.gaiterjones.com](http://blog.gaiterjones.com)
	@twitter		twitter.com/gaiterjones
	
### Requirements

* PHP5.x/MYSQL

* Magento 1.3+

### Installation

Copy the application files to a web accessible folder.

You may install the application in your Magento folder but you should secure the installation folder, as there is no application security.

Configure the application files in the config folder, you must configure the full path to the configuration .ini file, and the path to your Magento installation.

Load application URL into your browser, the application will load and you should see your current Magento orders.

### Usage

You can change the time range for the orders, the default is set in the config files, but you can also click the time range to edit it.

Click orders to select them, or double click one order to view it.

Selected orders can be viewed or printed by clicking the Preview or Print buttons. Click the refresh button to reload the order view.

Printed orders will be noted / acknowledged, for this to work the cache folder must be writeable by the WWW user or group.

## License

The MIT License (MIT)
Copyright (c) 2013 Peter Jones

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.