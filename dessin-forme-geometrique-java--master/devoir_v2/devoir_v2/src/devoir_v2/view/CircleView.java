package devoir_v2.view;

import java.awt.Color;
import java.awt.Graphics;
import java.awt.Graphics2D;

import devoir_v2.model.Circle;
import devoir_v2.model.Shape;

public class CircleView implements ShapeView {
	public void DrawShape(Graphics g, Shape p) { // method that draws the Circle
		Graphics2D g2d = (Graphics2D) g;
		Circle c = (Circle) p;
		// CIRCLE
		g2d.setPaint(Color.red);
		g2d.drawOval((int) (c.center.getX() - (c.radius / 2)), (int) (c.center.getY() - (c.radius / 2)), (int) c.radius,
				(int) c.radius);

	}

}
