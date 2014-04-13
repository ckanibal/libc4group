libc4group
==========
A (hopefully) sophisticated approach to creating a
full-featured php library to read/(write?) RedWolfDesign's Clonk(tm) files.

Examples
--------

```
$missions = libc4group\C4Group::fromFile("files/Missions.c4f");
$img = $missions->get('Frontier.c4s')->getTitleImage();
$script = $missions->get('Rocks.c4s')->get('System.c4g')->get('BigRock.c');
$author = $missions->get('Low.c4s')->get('Bread.c4d')->getAuthor();
```