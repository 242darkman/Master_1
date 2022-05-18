package devoir_v2.listenerPattern;

import java.util.ArrayList;
import java.util.Random;

import devoir_v2.model.Point;
import devoir_v2.model.Shape;

public class ContainerShapes extends ListenableModel {
	public ArrayList<Shape> shapes; // this class contains all the shapes that are shown in the interface
	public Point[] points;

	public ContainerShapes(int num_points, int w, int h, int padding) {// constructor, i added the padding so that the
																		// points
		// wont be so close to the edge
		listeners = new ArrayList<ChangeListener>();
		shapes = new ArrayList<Shape>();
		Random r = new Random();
		points = new Point[num_points];
		for (int i = 0; i < num_points; i++) {
			float x = Math.abs(r.nextInt()) % w;
			float y = Math.abs(r.nextInt()) % h;
			if (x < padding) {
				x = x + padding;
			}
			if (x > (w - padding)) {
				x = x - padding;
			}
			if (y > (h - padding)) {
				y = y - padding;
			}
			if (y < padding) {
				y = y + padding;
			}

			points[i] = new Point(x, y);
		}
		fireChange(); // fires changes everytime we add a shape, change a shape,move a shape resize or
						// add points
	}

	public ContainerShapes() {
		// TODO Auto-generated constructor stub
	}

	public void addShape(Shape shape) {
		shapes.add(shape);
		fireChange(); // fires changes everytime we add a shape, change a shape,move a shape resize or
						// add points
	}

	public void removeShape(Shape shape) {
		shapes.remove(shape);
		fireChange(); // fires changes everytime we add a shape, change a shape,move a shape resize or
						// add points
	}

	public void moveShape(Shape shape, float x, float y) {
		for (int i = 0; i < shapes.size(); i++) {
			if (shapes.get(i).center.getX() == shape.center.getX()
					&& shapes.get(i).center.getY() == shape.center.getY()) { // using this to
				// identify the
				// shape
				shapes.get(i).move2(x, y);
				fireChange();// fires changes everytime we add a shape, change a shape,move a shape resize or
								// add points

			}

		}

	}

	public void resizeShape(Shape shape, float x, float y) {
		for (int i = 0; i < shapes.size(); i++) {
			if (shapes.get(i).center.getX() == shape.center.getX()
					&& shapes.get(i).center.getY() == shape.center.getY()) { // using this to
				// identify the
				// shape
				// changing the center is not enough to move the shape, we have to move the
				// other points
				shapes.get(i).resize(x, y);

				fireChange();// fires changes everytime we add a shape, change a shape,move a shape resize or
								// add points
			}
		}
	}

	public void resizeShapeAfterCreate(Shape shape, float x, float y) {
		for (int i = 0; i < shapes.size(); i++) {
			if (shapes.get(i).center.getX() == shape.center.getX()
					&& shapes.get(i).center.getY() == shape.center.getY()) { // using this to
				// identify the
				// shape
				// changing the center is not enough to move the shape, we have to move the
				// other points
				shapes.get(i).resize_create(x, y);

				fireChange();// fires changes everytime we add a shape, change a shape,move a shape resize or
								// add points
			}
		}
	}

	public boolean won() { // a function that checks if we won
		boolean point_in_shape;
		for (int i = 0; i < this.points.length; i++) {
			point_in_shape = false;
			for (int j = 0; j < this.shapes.size(); j++) {
				if (this.shapes.get(j).contains(this.points[i])) {
					point_in_shape = true;
				}
			}
			if (point_in_shape == false) {
				return false;

			}
		} // we win only if all the points are inside the shapes
		return true;

	}

	public float calculate_score(int surface_width, int surface_height) { // a function that checks the score based on
																			// the
		// surface width and the sum surface of all of our
		// shapes
		float sum = 0;
		for (int i = 0; i < this.shapes.size(); i++) {
			sum = sum + this.shapes.get(i).surface;
		}
		return (surface_width * surface_height) / (surface_width * surface_height + sum);
	}

	public void changeContainer(ContainerShapes cs) {
		this.shapes = cs.shapes; // method that basically changes the container shapes into another container
									// shapes
		fireChange(); // used for the observer (memento) pattern
	}

	/**
	 * @return the shapes
	 */
	public ArrayList<Shape> getShapes() {
		return shapes;
	}

	/**
	 * @param shapes the shapes to set
	 */
	public void setShapes(ArrayList<Shape> shapes) {
		this.shapes = shapes;
	}

	/**
	 * @return the points
	 */
	public Point[] getPoints() {
		return points;
	}

	/**
	 * @param points the points to set
	 */
	public void setPoints(Point[] points) {
		this.points = points;
	}
	
	

}
