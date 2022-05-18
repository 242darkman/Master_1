package devoir_v2.statePattern;

import java.awt.event.MouseEvent;

import devoir_v2.listenerPattern.ContainerShapes;
import devoir_v2.model.Point;
import devoir_v2.model.Rectangle;
import devoir_v2.observerPattern.Memento;

public class CreateRectangleState implements State {
	static int selected_shape = -1;
	public static boolean default_create = true;

	@Override
	public void mouseClicked(MouseEvent e, ContainerShapes cs) {
		if (default_create) {
			Rectangle r = new Rectangle(e.getX(), e.getY(), 100, 200); // creates a rectangle with the cursor's position
																		// as
																		// a center
			cs.addShape(r);
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

			Rectangle r = new Rectangle(e.getX(), e.getY(), 10, 20); // creates a rectangle with the cursor's position
																		// as
			cs.addShape(r);
		}
		// TODO Auto-generated method stub

	}

	@Override
	public void mouseReleased(MouseEvent e, ContainerShapes cs) {
		selected_shape = -1;// if we release the mouse we deselect the shape
		if (!default_create) {

			// TODO Auto-generated method stub
			Memento.addContainerShapes(cs); // saving this state for later (undo and redo)
		}
	}

}
