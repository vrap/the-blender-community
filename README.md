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
