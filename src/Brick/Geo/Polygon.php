<?php

namespace Brick\Geo;

/**
 * A Polygon is a planar Surface representing a multisided geometry.
 * It is defined by a single exterior boundary and zero or more interior boundaries,
 * where each interior boundary defines a hole in the Polygon.
 */
class Polygon extends Surface implements \Countable, \IteratorAggregate
{
    /**
     * An array of LinearRing objects, the first one representing
     * the exterior ring, and the (optional) other ones representing
     * the interior rings of the Polygon.
     *
     * @var array
     */
    protected $rings = [];

    /**
     * Class constructor
     * Internal use only, consumer code must use factory() instead
     *
     * @param array $rings An array on LinearRing objects
     * @throws GeometryException
     */
    protected function __construct(array $rings)
    {
        if (count($rings) == 0) {
            throw new GeometryException('A Polygon must be constructed with at least one ring (the exterior ring)');
        }

        foreach ($rings as $ring) {
            $this->addRing($ring);
        }
    }

    /**
     * Internal function for the class constructor.
     *
     * @param LinearRing $ring
     */
    private function addRing(LinearRing $ring)
    {
        $this->rings[] = $ring;
    }

    /**
     * @param  LinearRing[] $rings
     * @return Polygon
     */
    public static function factory(array $rings)
    {
        if (count($rings) == 1) {
            if (count(reset($rings)) == 3 + 1) {
                return new Triangle($rings);
            }
        }

        return new Polygon($rings);
    }

    /**
     * @param  Point   $a
     * @param  Point   $b
     * @return Polygon
     */
    public static function createRectangle(Point $a, Point $b)
    {
        $x1 = min($a->x(), $b->x());
        $x2 = max($a->x(), $b->x());

        $y1 = min($a->y(), $b->y());
        $y2 = max($a->y(), $b->y());

        $p1 = Point::factory($x1, $y1);
        $p2 = Point::factory($x2, $y1);
        $p3 = Point::factory($x2, $y2);
        $p4 = Point::factory($x1, $y2);

        $ring = LinearRing::factory([$p1, $p2, $p3, $p4, $p1]);

        return Polygon::factory([$ring]);
    }

    /**
     * {@inheritdoc}
     */
    public function area()
    {
        return self::getService()->area($this);
    }

    /**
     * {@inheritdoc}
     */
    public function centroid()
    {
        return self::getService()->centroid($this);
    }

    /**
     * {@inheritdoc}
     */
    public function pointOnSurface()
    {
        return $this->exteriorRing()->startPoint();
    }

    /**
     * Returns the exterior ring of this Polygon.
     *
     * @return LineString
     */
    public function exteriorRing()
    {
        return reset($this->rings);
    }

    /**
     * Returns the number of interior rings in this Polygon.
     *
     * @return integer
     */
    public function numInteriorRings()
    {
        return count($this->rings) - 1;
    }

    /**
     * Returns the Nth interior ring for this Polygon as a LineString.
     * The ring number is 1-based.
     *
     * @param integer $n
     * @return LinearRing
     * @throws GeometryException
     */
    public function interiorRingN($n)
    {
        if (! is_int($n)) {
            throw new GeometryException('The ring number must be an integer');
        }

        if ($n < 1 || $n >= count($this->rings)) {
            throw new GeometryException('Ring number out of range');
        }

        return $this->rings[$n];
    }

    /**
     * {@inheritdoc}
     */
    public function geometryType()
    {
        return 'Polygon';
    }

    /**
     * {@inheritdoc}
     * A Polygon is a 2-dimensional geometric object.
     */
    public function dimension()
    {
        return 2;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return false;
    }

    /**
     * Returns the total number of rings in this Polygon (exterior + interior).
     * Required by interface Countable.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->rings);
    }

    /**
     * Required by interface InteratorAggregate.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->rings);
    }
}
