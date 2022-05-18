package devoir_v2.model;

public class Circle extends Shape {
	public float radius;

	float calculate_surface() {
		return (float) (Math.PI * (radius / 2) * (radius / 2));
	}

	public Circle(float x, float y, float radius) { // constructor
		this.center = new Point(x, y);
		this.radius = radius;
		this.surface = calculate_surface();

	}

	public boolean contains(Point point) { // method that checks if a point is inside the circle
		float distance = (float) Math.sqrt((center.getX() - point.getX()) * (center.getX() - point.getX())
				+ (center.getY() - point.getY()) * (center.getY() - point.getY()));
		return (distance <= (radius / 2));
	};

	void move(float x, float y) { // method that moves the circle
		this.center = new Point(x, y);
	}

	/*
	 * so first before we do this we have to save our inital point then when we drag
	 * the mouse we calculate the dx and the dy
	 **/
	public void move2(float dx, float dy) { // method that moves the circle with an offset (dx,dy)
		move(this.center.getX() + dx, this.center.getY() + dy);
	}

	public void resize(float x, float y) { // method that resizes the circle
		float distance = (float) Math.sqrt((this.center.getX() - x) * (this.center.getX() - x)
				+ (this.center.getY() - y) * (this.center.getY() - y));
		this.radius = distance;
		this.surface = calculate_surface();
	}

	@Override
	public void resize_create(float x, float y) {// method that resizes the circle when it is first created
		resize(x, y);

	}

	/**
	 * @return the radius
	 */
	public float getRadius() {
		return radius;
	}

	/**
	 * @param radius the radius to set
	 */
	public void setRadius(float radius) {
		this.radius = radius;
	}
	
	


}
