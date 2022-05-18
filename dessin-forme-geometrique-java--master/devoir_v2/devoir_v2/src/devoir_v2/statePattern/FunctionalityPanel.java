package devoir_v2.statePattern;

import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import javax.swing.ButtonGroup;
import javax.swing.JButton;
import javax.swing.JPanel;
import javax.swing.JRadioButton;

import devoir_v2.listenerPattern.ContainerShapes;
import devoir_v2.observerPattern.Memento;

public class FunctionalityPanel extends JPanel implements ActionListener {
	private static final long serialVersionUID = 1L;
	ContainerShapes cs;
	Context context;

	JButton create_circle_button;
	JButton create_triangle_button;
	JButton create_rectangle_button;
	JButton delete_shape_button;
	JButton move_shape_button;
	JButton resize_shape_button;
	JButton delete_shape;
	JButton undo_button;
	JButton redo_button;

	ButtonGroup group;
	JRadioButton default_create_button;
	JRadioButton custom_create_button;

	public FunctionalityPanel(Context context, ContainerShapes cs) {
		JButton create_circle_button = new JButton("Create Circle");
		JButton create_triangle_button = new JButton("Create Triangle");
		JButton create_rectangle_button = new JButton("Create Rectangle");
		JButton delete_shape_button = new JButton("Delete Shape");
		JButton move_shape_button = new JButton("Move Shape");
		JButton resize_shape_button = new JButton("Resize Shape");
		JButton undo_button = new JButton("Undo");
		JButton redo_button = new JButton("Redo");

		group = new ButtonGroup();
		custom_create_button = new JRadioButton("Custom");
		default_create_button = new JRadioButton("Default");
		group.add(default_create_button);
		group.add(custom_create_button);

		this.context = context;
		this.cs = cs; // here we need the current cs so we can change it with undo and redo
		add(default_create_button);
		default_create_button.setSelected(true);
		add(custom_create_button);
		add(create_circle_button);
		add(create_triangle_button);
		add(create_rectangle_button);
		add(delete_shape_button); // adding buttons to the panel
		add(move_shape_button);
		add(resize_shape_button);
		add(undo_button);
		add(redo_button);

		create_circle_button.addActionListener(this);
		create_triangle_button.addActionListener(this);
		create_rectangle_button.addActionListener(this);
		delete_shape_button.addActionListener(this); // adding action listeners to the buttons
		move_shape_button.addActionListener(this);
		resize_shape_button.addActionListener(this);
		undo_button.addActionListener(this);
		redo_button.addActionListener(this);
		default_create_button.addActionListener(this);
		custom_create_button.addActionListener(this);
	}

	@Override
	public void actionPerformed(ActionEvent e) {

		if (e.getSource() instanceof JButton) { // if it is a button we do this
			if (((JButton) e.getSource()).getText().equals("Create Circle")) {
				context.setState(new CreateCircleState());
			} else if (((JButton) e.getSource()).getText().equals("Create Triangle")) {
				context.setState(new CreateTriangleState());
			} else if (((JButton) e.getSource()).getText().equals("Create Rectangle")) {
				context.setState(new CreateRectangleState());
			} else if (((JButton) e.getSource()).getText().equals("Delete Shape")) {
				context.setState(new DeleteState()); // switching the state depending on button
			} else if (((JButton) e.getSource()).getText().equals("Move Shape")) {
				context.setState(new MoveState());
			} else if (((JButton) e.getSource()).getText().equals("Resize Shape")) {
				context.setState(new ResizeState());
			} else if (((JButton) e.getSource()).getText().equals("Undo")) { // if we hit undo or redo we won't switch
				// the state, we will just modify it from
				// here
				cs.changeContainer(Memento.undo()); // if we click on undo, we change the current container to the
													// previously saved container on memento
			} else if (((JButton) e.getSource()).getText().equals("Redo")) {

				cs.changeContainer(Memento.redo()); // if we click on redo, we change the current container to the
													// previously saved container on memento
			}
		} else if (e.getSource() instanceof JRadioButton) {
			if (((JRadioButton) e.getSource()).getText().equals("Custom")) {
				CreateCircleState.default_create = false;
				CreateTriangleState.default_create = false;
				CreateRectangleState.default_create = false;
			} else if (((JRadioButton) e.getSource()).getText().equals("Default")) {
				CreateCircleState.default_create = true;
				CreateTriangleState.default_create = true;
				CreateRectangleState.default_create = true;
			}
		}

	}
}
