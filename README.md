Symfony 3.3 REST API for email handling - (recruitment task, not for reuse)
========================
(This is test app with solutions that should not be implemented in production environment)

App has endpoints to create email, get one email, get all emails from db. 
There is also an option to send all pending emails usung process in background.

Using:
* [**FOS RestBundle**][1] - REST API architecture
* [**JMS Serializer**][2] - serializing objects to JSON
* [**NelmioApiDocBundle**][3] - documentation for API

[1]: https://symfony.com/doc/master/bundles/FOSRestBundle/index.html
[2]: http://jmsyst.com/bundles/JMSSerializerBundle
[3]: https://github.com/nelmio/NelmioApiDocBundle
