package devoir_v2.model;

import java.awt.Polygon;

public class Triangle extends Shape {
	public Point points[];
	public float side;

	float calculate_surface() {
		return side * side * ((float) Math.sqrt(3) / 4);
	}

	public Triangle(float x, float y, float side) { // constructor
		this.center = new Point(x, y);
		float distance_center = (float) (side / Math.sqrt(3));
		float distance_mileu = (float) (Math.sqrt((distance_center * distance_center) - ((side / 2) * (side / 2))));
		this.points = new Point[3];
		points[0] = new Point(x, (y - distance_center));
		points[1] = new Point(x + (side / 2), y + distance_mileu);
		points[2] = new Point(x - (side / 2), y + distance_mileu);
		this.side = side;
		this.surface = calculate_surface();
	}

	public boolean contains(Point point) { // method that checks if the point is inside the triangle

		Polygon p = new Polygon();
		p.addPoint((int) points[0].getX(), (int) points[0].getY());
		p.addPoint((int) points[1].getX(), (int) points[1].getY());
		p.addPoint((int) points[2].getX(), (int) points[2].getY());
		return p.contains((int) point.getX(), (int) point.getY());
	};

	public void move(float x, float y) { // method that moves the triangle
		this.center = new Point(x, y);
		float distance_center = (float) (side / Math.sqrt(3));
		float distance_mileu = (float) (Math.sqrt((distance_center * distance_center) - ((side / 2) * (side / 2))));
		this.points[0] = new Point(x, (y - distance_center));
		this.points[1] = new Point(x + (side / 2), y + distance_mileu);
		this.points[2] = new Point(x - (side / 2), y + distance_mileu);

	}

	public void move2(float dx, float dy) { // method that moves the triangle with an offset (dx,dy)
		move(this.center.getX() + dx, this.center.getY() + dy);
	}

	public void resize(float x, float y) { // method that resizes the triangle
		float distance = (float) Math.sqrt((this.center.getX() - x) * (this.center.getX() - x)
				+ (this.center.getY() - y) * (this.center.getY() - y));
		this.side = distance;
		float distance_center = (float) (side / Math.sqrt(3));
		float distance_mileu = (float) (Math.sqrt((distance_center * distance_center) - ((side / 2) * (side / 2))));
		this.points[0] = new Point(center.getX(), (center.getY() - distance_center));
		this.points[1] = new Point(center.getX() + (side / 2), center.getY() + distance_mileu);
		this.points[2] = new Point(center.getX() - (side / 2), center.getY() + distance_mileu);
		this.surface = calculate_surface();

	}

	@Override
	public void resize_create(float x, float y) { // method that resizes the triangle when it is first created
		resize(x, y);

	}

	public Point[] getPoints() {
		return this.points;
	}

}
