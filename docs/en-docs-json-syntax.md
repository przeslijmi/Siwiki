@{md.include::file:elements/header.en.md}

# `_docs.json` Syntax

That file is created as a result of [`PhpDocGenerator`](class-Przeslijmi_Siwiki_PhpDocGenerator.!) and contains documentation of all classes, methods and properties inside one or more requested apps.

## Main level

There is one main node for every app, ie.:

```json
{
    "przeslijmi\/siwiki": {
        ...
    },
    "company\/app": {
        ...
    }
}
```

## App level

Inside every app there are two nodes:
  - `composer` that contains copy of app's `composer.json` contentents,
  - `classes` that contains details of all classes inside app as on objects, which key is full class name (including namespace); contents of every class doc are described below.

## Class level

Every class has nodes:
  - `uri` with direct uri to file on drive with that class,
  - `namespace`,
  - `uses` with array of classes that this class is using (key is an class alias, value is class full name with namespace)
  - `class` as an object wich contains:
    - `name` with name of this class (ie. last part of namespace),
    - `type` (ie. `class` or `interface`),
    - `isFinal`,
    - `isAbstract` ,
    - `extends` with full name of class that this one extends from,
    - `implements` with array of short names of classes from this class implements,
    - `comments` (see **Comments level** heading),
  - `properties` with list of properties (see **Properties and methods level** heading),
  - `methods` with list of methods (see **Properties and methods level** heading).

## Properties and methods level

Both properties and methods are very similar. Every object has nodes:
  - `scope` with **private**, **protected** or **public** values,
  - `isStatic`,
  - `name`,
  - `posStart` with integer indicating start position of this method inside class,
  - `defValue` which is available only for properties - but not in fact used,
  - `comments` (see **Comments level** heading).

## Comments level

Every `comments` nodes have to child nodes:
  - `title` with first line of defined in PHP comments,
  - `contents` with the rest of defined in PHP comments.

**BEWARE** This part will receive enhancements in future versions - especially with recognizing tags.

@{html.include::file:elements/footer.en.html}
