package devoir_v2.model;

import javax.swing.JComponent;

public abstract class Shape {
	public Point center; // center of the shape

	public float surface;// surface of the shape

	public boolean contains(Point point) {// method that checks if a point is inside the shape
		return false;
	};

	void move(float x, float y) {// method that moves
	};

	public void move2(float dx, float dy) {// method that moves with an offset (dx,dy)
	};

	public void resize(float x, float y) {// method that resizes
	};

	public boolean isMoving = false; // flag that checks if a shape is moving or not

	public void resize_create(float x, float y) {// method that resizes when it is first created
	}

	public  Point getCenter() {
		return this.center;
	}

}
