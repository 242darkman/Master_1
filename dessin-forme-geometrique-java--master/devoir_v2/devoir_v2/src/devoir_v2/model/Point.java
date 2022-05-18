package devoir_v2.model;

public class Point { // point class used to represent the points of the table and points of shapes
	private float x;

	public float getX() {
		return x;
	}

	public void setX(float x) {
		this.x = x;
	}

	public float getY() {
		return y;
	}

	public void setY(float y) {
		this.y = y;
	}

	private float y;

	public Point(float x, float y) {
		this.x = x;
		this.y = y;
	}
}
