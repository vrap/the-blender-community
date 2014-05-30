# The Blender Community Server

## Configuration

Create a config.ini file in the root directory with database configuration information. Two drivers (**MySQL**, **SQLite**) actually exists, and it's up to you to choose which one you want, the following example use the MySQL one :

```ini
[database]
driver=mysql
host=localhost
port=3306
user=username
password=password
name=database_name
```

You also need to add some informations about the community, you can add whatever you want to this configuration section all of the items present here will be sent to the client when asking the "infos" endpoint. The minimum requirements is a **name** for the community and the **master** uri like in the example above :

```ini
[community]
name=The Blender community
master=http://master.the-blender.io/
```

A usually parameter that is added in the community section is a **url** of the community website.

A good example of configuration file can be like that :

```ini
[database]
driver=mysql
host=localhost
port=3306
user=CommunitySecretDbUser
password=CommunityDbSecretPassword
name=the-blender-community

[community]
name=The Blender community
url=http://community.the-blender.io/
master=http://master.the-blender.io/
```