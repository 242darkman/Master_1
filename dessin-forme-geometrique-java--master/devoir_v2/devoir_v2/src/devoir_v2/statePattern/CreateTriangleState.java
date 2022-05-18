package devoir_v2.statePattern;

import java.awt.event.MouseEvent;

import devoir_v2.listenerPattern.ContainerShapes;
import devoir_v2.model.Point;
import devoir_v2.model.Triangle;
import devoir_v2.observerPattern.Memento;

public class CreateTriangleState implements State {
	static int selected_shape = -1;
	public static boolean default_create = true;

	@Override
	public void mouseClicked(MouseEvent e, ContainerShapes cs) {
		if (default_create) {
			Triangle t = new Triangle(e.getX(), e.getY(), 100); // creates a triangle with the cursor's position as a
																// center
			cs.addShape(t);
			Memento.addContainerShapes(cs); // saving this state for later (undo and redo)
		}
	}

	public void mouseDragged(MouseEvent e, ContainerShapes cs) {
		if (!default_create) {
			if (selected_shape == -1) {
				for (int i = 0; i < cs.shapes.size(); i++) {

					if ((cs.shapes.get(i).contains(new Point(e.getX(), e.getY())))) {
						selected_shape = i;
					}
				}
			} else {
				cs.resizeShapeAfterCreate(cs.shapes.get(selected_shape), (float) e.getX(), (float) e.getY());
			}
		}
	}

	@Override
	public void mousePressed(MouseEvent e, ContainerShapes cs) {
		if (!default_create) {
			Triangle t = new Triangle(e.getX(), e.getY(), 30); // creates a triangle with the cursor's position as a
																// center
			cs.addShape(t);
			// TODO Auto-generated method stub

		}
	}

	@Override
	public void mouseReleased(MouseEvent e, ContainerShapes cs) {
		selected_shape = -1; // if we release the mouse we deselect the shape
		if (!default_create) {

			Memento.addContainerShapes(cs); // when we release the mouse, that means we are done creating and we should
		}
	}

}
