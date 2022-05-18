package devoir_v2.model;

import java.awt.Polygon;

public class Rectangle extends Shape {
	public Point points[];
	public float height;
	public float width;

	public float calculate_surface() {
		return height * width;
	}

	public Rectangle(float x, float y, float height, float width) { // constructor

		this.center = new Point(x, y);
		this.points = new Point[4];
		points[0] = new Point((x - (width / 2)), (y) - (height / 2));
		points[1] = new Point((x + (width / 2)), (y) - (height / 2));
		points[2] = new Point((x + (width / 2)), (y) + (height / 2));
		points[3] = new Point((x - (width / 2)), (y) + (height / 2));
		this.height = height;
		this.width = width;
		this.surface = calculate_surface();
	}

	public boolean contains(Point point) { // method that checks if a point is inside the rectangle

		Polygon p = new Polygon();
		p.addPoint((int) points[0].getX(), (int) points[0].getY());
		p.addPoint((int) points[1].getX(), (int) points[1].getY());
		p.addPoint((int) points[2].getX(), (int) points[2].getY());
		p.addPoint((int) points[3].getX(), (int) points[3].getY());

		return p.contains((int) point.getX(), (int) point.getY());
	};

	void move(float x, float y) {// method that moves the rectangle
		this.center = new Point(x, y);
		this.points[0] = new Point((x - (width / 2)), (y) - (height / 2));
		this.points[1] = new Point((x + (width / 2)), (y) - (height / 2));
		this.points[2] = new Point((x + (width / 2)), (y) + (height / 2));
		this.points[3] = new Point((x - (width / 2)), (y) + (height / 2));

	}

	public void move2(float dx, float dy) { // method that moves the rectangle with an offset(dx,dy)
		move(this.center.getX() + dx, this.center.getY() + dy);
	}

	public void resize(float x, float y) {// method that resizes the rectangle
		float d1 = calculate_distance(new Point(x, y),
				new Point((this.points[0].getX() + this.points[1].getX()) / 2, (this.points[0].getY())));
		float d2 = calculate_distance(new Point(x, y),
				new Point(this.points[1].getX(), ((this.points[1].getY() + this.points[2].getY()) / 2)));
		float d3 = calculate_distance(new Point(x, y),
				new Point((this.points[0].getX() + this.points[1].getX()) / 2, (this.points[2].getY())));
		float d4 = calculate_distance(new Point(x, y),
				new Point(this.points[0].getX(), ((this.points[3].getY() + this.points[0].getY()) / 2)));
		float d[] = { d1, d2, d3, d4 };
		int index = 0;
		float min = d[0];
		for (int i = 1; i < 4; i++) {
			if (min > d[i]) {
				min = d[i];
				index = i;
			}

		}
		switch (index) {
		case 0:
			this.points[0].setY(y);
			this.points[1].setY(y);
			this.height = calculate_distance(this.points[0], this.points[3]);
			this.center = find_center(this);
			this.surface = calculate_surface();
			break;
		case 1:
			this.points[1].setX(x);
			this.points[2].setX(x);
			this.width = calculate_distance(this.points[0], this.points[1]);
			this.center = find_center(this);
			this.surface = calculate_surface();
			break;
		case 2:
			this.points[2].setY(y);
			;
			this.points[3].setY(y);
			;
			this.height = calculate_distance(this.points[0], this.points[3]);
			this.center = find_center(this);
			this.surface = calculate_surface();
			break;
		case 3:
			this.points[0].setX(x);
			this.points[3].setX(x);
			this.width = calculate_distance(this.points[0], this.points[1]);
			this.center = find_center(this);
			this.surface = calculate_surface();
			break;

		}
	}

	public void resize_create(float x, float y) {// method that resizes the rectangle when it is first created
		this.height = Math.abs(this.center.getY() - y) * 2;
		this.width = Math.abs(this.center.getX() - x) * 2;
		this.points[0] = new Point((center.getX() - (width / 2)), (center.getY()) - (height / 2));
		this.points[1] = new Point((center.getX() + (width / 2)), (center.getY()) - (height / 2));
		this.points[2] = new Point((center.getX() + (width / 2)), (center.getY()) + (height / 2));
		this.points[3] = new Point((center.getX() - (width / 2)), (center.getY()) + (height / 2));
		this.surface = calculate_surface();
	}

	public float calculate_distance(Point a, Point b) { // method that calculates the distance between two points

		return (float) Math.sqrt(
				((a.getX() - b.getX()) * (a.getX() - b.getX())) + ((a.getY() - b.getY()) * (a.getY() - b.getY())));
	}

	public Point find_center(Rectangle r) { // method that finds the center of a rectangle
		return new Point((this.points[0].getX() + this.points[1].getX()) / 2,
				(this.points[0].getY() + this.points[3].getY()) / 2);
	}

	public Point[] getPoints() {
		return this.points;
	}

}
