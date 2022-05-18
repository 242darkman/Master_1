package devoir_v2.statePattern;

import java.awt.event.MouseEvent;

import devoir_v2.listenerPattern.ContainerShapes;
import devoir_v2.model.Point;
import devoir_v2.observerPattern.Memento;

public class MoveState implements State {
	Point init_point;
	Point current_point;
	static int selected_shape = -1;

	@Override
	public void mouseClicked(MouseEvent e, ContainerShapes cs) {

	}

	@Override
	public void mousePressed(MouseEvent e, ContainerShapes cs) {
	}

	@Override
	public void mouseDragged(MouseEvent e, ContainerShapes cs) {

		for (int i = 0; i < cs.shapes.size(); i++) {// for each

			if (selected_shape == -1) {// if we have not selected a shape yet we should select one
				if (cs.shapes.get(i).contains(new Point(e.getX(), e.getY()))) {
					selected_shape = i;
				}
			} else {
				if (cs.shapes.get(selected_shape).isMoving == false) {
					// we have to stop moving all others
					for (int j = 0; j < cs.shapes.size(); j++) {// for each
						if (selected_shape != j) {
							cs.shapes.get(j).isMoving = false;
						}
					}
					init_point = new Point((float) e.getX(), (float) e.getY());
				}
				current_point = new Point((float) e.getX(), (float) e.getY());
				float dx = current_point.getX() - init_point.getX();
				float dy = current_point.getY() - init_point.getY();
				init_point = current_point;
				cs.moveShape(cs.shapes.get(selected_shape), dx, dy);
				cs.shapes.get(selected_shape).isMoving = true;
			}
		}

	}

	@Override
	public void mouseReleased(MouseEvent e, ContainerShapes cs) {
		selected_shape = -1; // we deselect the shape
		for (int i = 0; i < cs.shapes.size(); i++) {
			cs.shapes.get(i).isMoving = false;
		}
		Memento.addContainerShapes(cs); // saving this state for later (undo and redo)

	}

}
