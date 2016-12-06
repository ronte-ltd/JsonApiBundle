
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ronte-ltd/JsonApiBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ronte-ltd/JsonApiBundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ronte-ltd/JsonApiBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ronte-ltd/JsonApiBundle/?branch=master)

# Symfony3 JsonApi REST Bundle

## Install

### Composer
```sh
composer require ronte-ltd/json-api-bundle
```

## Init

### AppKernel.php
```php
new RonteLtd\JsonApiBundle\RonteLtdJsonApiBundle()
```

### config.yml
```yaml
ronte_ltd_json_api:
    jsonapi:
        version: "1.0"
```

## Use

### Entity

```php
use RonteLtd\JsonApiBundle\Annotation as JsonApi;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * City
 *
 * @ORM\Table(name="geo_cities")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Common\EntityRepository")
 * @JsonApi\ObjectNormalizer(name="city")
 */
class City extends Entity
{
    /**
     * @var string
     *
     * @ORM\Column(name="titleShort", type="string", length=255, nullable=true)
     *
     * @JsonApi\Attribute(name="title_short")
     * @Groups({"default", "other"})
     */
    private $titleShort;
    
    /**
     * Country
     *
     * @var Country
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="cities")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @JsonApi\Relationship(name="countries")
     * @Groups({"default"})
     */
    protected $country;
    
```

### Controller

```php
use RonteLtd\JsonApiBundle\Controller\AbstractRestController;
use RonteLtd\JsonApiBundle\Annotation as JsonApi;

class TestController extends AbstractRestController
{
    /**
     * @param $id
     * 
     * @JsonApi\Links({"self":"http://mydomain.com/api/v1/city"})
     * @JsonApi\Meta({"authors":{"Ruslan","Alexey"}})
     */
    public function getAction($id)
    {
        $city = $this->get('doctrine.orm.default_entity_manager')
                        ->getRepository('AppBundle:City')
                        ->find($id);

        return $this->renderJsonApi($city);
    }

    /**
     * @param $id
     * 
     * @JsonApi\Links({"self":"http://mydomain.com/api/v1/cities"})
     * @JsonApi\Meta({"authors":{"Ruslan","Alexey"}})
     */
    public function getAllAction()
    {
        $result = $this->get('doctrine.orm.default_entity_manager')
            ->createQuery('SELECT c FROM AppBundle\Entity\City c')
            ->getResult();

        return $this->renderJsonApi($result);
    }
```

### Normalizer

```php
use RonteLtd\JsonApiBundle\Serializer\Normalizer\Collection;

$data = []; //Some objects collection or object

$collection = new Collection($data);

//Optional http://jsonapi.org/format/#document-top-level
$collection->setJsonapi(['version' => '1.0']);
$collection->setMeta(['authors' => ['Ruslan', 'Alexey']]); //Some metadata
$collection->setLinks(['self' => 'http://mydomain.com/api/v1/']); //JsonApi links

$jsonapiData = $this->get('serializer')->normalize($collection, 'json',
    [
        'groups' => ['default'],
        'enable_max_depth' => true,
//                'depth_AppBundle\Entity\City::country' => 4
    ]);
```
     
### Serializer

```php 
use RonteLtd\JsonApiBundle\Serializer\Normalizer\Collection;

$data = []; //Some objects collection or object

$collection = new Collection($data);

//Optional http://jsonapi.org/format/#document-top-level
$collection->setJsonapi(['version' => '1.0']);
$collection->setMeta(['authors' => ['Ruslan', 'Alexey']]); //Some metadata
$collection->setLinks(['self' => 'http://mydomain.com/api/v1/']); //JsonApi links

$jsonApi = $this->get('serializer')->serialize($collection, 'json');
```

## Road map

- [ ] Routing
- [ ] Annotations
- [ ] Abstract controllers
- [ ] jsonapi transformers
- [ ] Resource controllers
- [ ] Docs

