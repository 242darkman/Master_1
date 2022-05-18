package devoir_v2.observerPattern;

import java.util.ArrayList;

import devoir_v2.listenerPattern.ContainerShapes;
import devoir_v2.model.Circle;
import devoir_v2.model.Point;
import devoir_v2.model.Rectangle;
import devoir_v2.model.Shape;
import devoir_v2.model.Triangle;

public class Memento { // this class has all of the instances of container shapes ever created
	static ArrayList<ContainerShapes> cs_list = new ArrayList<ContainerShapes>();
	static int index = -1;
	static boolean top_of_stack = true;
	static boolean bottom_of_stack = true;

	public static void addContainerShapes(ContainerShapes cs) {
		// we have to copy the container by value and not by reference
		// which means allocating spaces of memory with new
		ContainerShapes temp_container = new ContainerShapes();
		temp_container.points = new Point[cs.points.length];
		for (int i = 0; i < cs.points.length; i++) {
			temp_container.points[i] = new Point(cs.points[i].getX(), cs.points[i].getY());
		} // making copies of points
		temp_container.shapes = new ArrayList<Shape>();
		for (int i = 0; i < cs.shapes.size(); i++) {
			if (cs.shapes.get(i) instanceof Circle) {
				Circle C = new Circle(((Circle) cs.shapes.get(i)).center.getX(),
						((Circle) cs.shapes.get(i)).center.getY(), ((Circle) cs.shapes.get(i)).radius);
				temp_container.shapes.add(C);
			} // making copies of shapes

			if (cs.shapes.get(i) instanceof Triangle) {
				Triangle T = new Triangle(((Triangle) cs.shapes.get(i)).center.getX(),
						((Triangle) cs.shapes.get(i)).center.getY(), ((Triangle) cs.shapes.get(i)).side);
				temp_container.shapes.add(T);
			}
			if (cs.shapes.get(i) instanceof Rectangle) {
				Rectangle R = new Rectangle(((Rectangle) cs.shapes.get(i)).center.getX(),
						((Rectangle) cs.shapes.get(i)).center.getY(), ((Rectangle) cs.shapes.get(i)).height,
						((Rectangle) cs.shapes.get(i)).width);
				temp_container.shapes.add(R);
			}

		}
		Memento.cs_list.add(temp_container);
		index++;
	}

	public static ContainerShapes undo() { // undo returns the previous saved state

		if (index >= 0) {
			if (top_of_stack) {
				index -= 2;
				top_of_stack = false;
			} else {
				index--;
			}
			return cs_list.get(index + 1);
		}
		bottom_of_stack = true;
		return null;// if we hit the bottom of the stack, we return null
	}

	public static ContainerShapes redo() {// redo goes back to the next saved state
		if (index == cs_list.size()) {
			top_of_stack = true;
			return null;// if we hit the top of the stack, we return null
		} else {
			if (bottom_of_stack) {
				index += 2;
				bottom_of_stack = false;
			} else {
				index++;
			}
			return cs_list.get(index);

		}
	}

}
