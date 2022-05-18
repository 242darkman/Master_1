package devoir_v2.view;

import java.awt.Color;
import java.awt.Graphics;
import java.awt.Graphics2D;

import devoir_v2.model.Rectangle;
import devoir_v2.model.Shape;

public class RectangleView implements ShapeView {
	public void DrawShape(Graphics g, Shape shape) { // method that draws the Rectangle
		Graphics2D g2d = (Graphics2D) g;
		Rectangle r = (Rectangle) shape;
		g2d.setPaint(Color.blue);
		int[] x = { (int) r.points[0].getX(), (int) r.points[1].getX(), (int) r.points[2].getX(),
				(int) r.points[3].getX() }; // so
		int[] y = { (int) r.points[0].getY(), (int) r.points[1].getY(), (int) r.points[2].getY(),
				(int) r.points[3].getY() }; // so
		g2d.drawPolygon(x, y, 4);

	}
}
